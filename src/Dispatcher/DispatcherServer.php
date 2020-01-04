<?php

namespace Src\Dispatcher;

use App\Kernel;
use Src\Core\App;
use Swoole\Server;
use Swoole\WebSocket\Server as WsServer;
use Swoole\WebSocket\Frame;
use Src\Helper\ErrorHelper;
use Src\Dispatcher\RequestServer;
use Src\Dispatcher\ResponseServer;
use Src\Helper\AnnotationResourceHelper;
use Src\RPC\Packet\Encoder;
use Src\Core\Contexts\RedisContext;
use Src\Core\Contexts\RequestContext;

class DispatcherServer
{
    private $app;
    private $routes;

    public function __construct(App $app)
    {
        $this->app = $app;
        // Get routes
        $bootScan = $this->app->get('config')->get('app.bootScan');
        $resource = new AnnotationResourceHelper($bootScan);
        $resource->scanNamespace();
        $resource->getDefinitions();

        $this->routes = $app->get('route_table')->all();
    }

    public function httpHandle(RequestServer $request, ResponseServer $response)
    {
        $this->beforeDispatch($request, $response);
        $replace_uri = preg_replace('/\d+/i', '{}', $request->request->server['request_uri']);
        $type = strtolower($request->request->server['request_method']);
        switch (isset($this->routes[$type . '@' . $replace_uri])) {
            case true:
                return $this->httpDispatch($request, $response, $this->routes[$type . '@' . $replace_uri]);
            case false:
                return error(ErrorHelper::ROUTE_ERROR_CODE, ErrorHelper::ROUTE_ERROR_MSG);
        }
    }

    public function httpDispatch(RequestServer $request, ResponseServer $response, $route)
    {
        preg_match('/\d+/i', $request->request->server['request_uri'], $params);
        $kernel = new Kernel($this->app);
        $middleware = $kernel->getMiddleware();
        $middleware = array_filter($middleware + [$kernel->getRouteMiddleware($route['middleware'])]);
        $destination = $this->getDestination($request, $response, $route['controller'], $route['method'], end($params));

        // Execution middleware
        $pipeline = array_reduce(
            array_reverse($middleware),
            $this->getInitialSlice(),
            $this->prepareDestination($destination)
        );

        $data = $pipeline($request);

        $response = RequestContext::getResponse();
        
        $response->end($data);

        $this->afterDispatch();
    }

    // Get Controller Closure
    public function getDestination(RequestServer $request, ResponseServer $response, string $controller, string $action, string $paraData)
    {
        return function () use ($controller, $request, $response, $action, $paraData) {
            return call_user_func_array([new $controller($request, $response), $action], [$paraData]);
        };
    }

    protected function getInitialSlice()
    {
        return function ($stack, $pipe) {
            return function (RequestServer $request) use ($stack, $pipe) {
                return (new $pipe())->handle($request, $stack);
            };
        };
    }

    protected function prepareDestination(\Closure $destination)
    {
        return function (RequestServer $request) use ($destination) {
            return $destination($request);
        };
    }

    protected function beforeDispatch(RequestServer $request, ResponseServer $response)
    {
        RequestContext::setRequest($request);
        RequestContext::setResponse($response);
    }

    protected function afterDispatch()
    {
        RequestContext::clearCidContext();
        RedisContext::clearCidContext();
    }
    
    public function wsHandle(WsServer $server, Frame $frame)
    {
        $request = $frame->data = json_decode($frame->data);
        if (is_null($request->data)) {
            return $server->push($frame->fd, json_encode(error(ErrorHelper::ROUTE_ERROR_CODE, ErrorHelper::ROUTE_ERROR_MSG)));
        }
        $type = strtolower($request->type);
        switch (isset($this->routes[$type . '@' . $request->url])) {
            case true:
                return $this->wsDispatch($server, $frame, $this->routes[$type . '@' . $request->url]);
            case false:
                return $server->push($frame->fd, json_encode(error(ErrorHelper::ROUTE_ERROR_CODE, ErrorHelper::ROUTE_ERROR_MSG)));
        }
    }

    public function wsDispatch(WsServer $server, Frame $frame, $route)
    {
        $server->task($frame, -1, [new $route['controller'], $route['method']]);
    }

    public function rpcHandle(Server $server, int $fd, string $data)
    {
        $rpcProtocol = Encoder::rpcDecode($data);
        $service_name = $rpcProtocol->getMethod();
        $proto_class_name = $rpcProtocol->getProtoClassName();
        $proto_str = $rpcProtocol->getProtoStr();
        switch (isset($this->routes['service@' . $service_name])) {
            case true:
                return $this->rpcDispatch($server, $fd, $this->routes['service@' . $service_name], $proto_class_name, $proto_str);
            case false:
                $server->close($fd);
                return $server->send($fd, json_encode(error(ErrorHelper::ROUTE_ERROR_CODE, ErrorHelper::ROUTE_ERROR_MSG)));
        }
    }

    public function rpcDispatch(Server $server, int $fd, array $route, string $proto_class_name, string $proto_str)
    {
        $proto = new $proto_class_name;
        $proto->mergeFromString($proto_str);
        $response = call_user_func([new $route['controller'], $route['method']], $proto);
        $status_len = strlen($response['status']);
        $code_len = strlen($response['code']);
        $str = pack("A{$status_len}A{$code_len}A*", $response['status'], $response['code'], json_encode($response['data'], JSON_UNESCAPED_UNICODE));
        $binary_str = str_to_bin($status_len.'-'.$code_len.'-'.$str);
        $server->send($fd, $binary_str."\r\n");
        $server->close($fd);
    }
}
