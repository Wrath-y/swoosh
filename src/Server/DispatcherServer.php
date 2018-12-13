<?php

namespace Src\Server;

use Src\App;
use App\Kernel;
use Src\Server\RequestServer;
use Src\Server\ResponseServer;
use Src\Resource\AnnotationResource;

class DispatcherServer
{
    public function __construct()
    {
        // Get routes
        $bootScan =  App::getSupport('config')->get('bootScan');
        $resource = new AnnotationResource($bootScan);
        $resource->scanNamespace();
        $resource->getDefinitions();
    }

    public function handle(RequestServer $request, ResponseServer $response, $route)
    {
        preg_match('/\d+/i', $request->request->server['request_uri'], $params);
        $kernel = new Kernel();
        $middleware = $kernel->getMiddleware();
        $middleware = $middleware + [$kernel->getRouteMiddleware($route['middleware'])];
        $destination = $this->getDestination($request, $response, $route['controller'], $route['method'], end($params));
        // Execution middleware
        $pipeline = array_reduce(
            array_reverse($middleware),
            $this->getInitialSlice(),
            $this->prepareDestination($destination)
        );
    }

    // Get Controller Closure
    public function getDestination(RequestServer $request, ResponseServer $response, string $controller, string $action, string $paraData)
    {
        return function () use ($controller, $request, $response, $action, $paraData) {
            return call_user_func_array([new $controller($request, $response), $action], $paraData);
        };
    }

    protected function getInitialSlice()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                return (new $pipe())->handle($passable, $stack);
            };
        };
    }

    protected function prepareDestination(\Closure $destination)
    {
        return function ($passable) use ($destination) {
            return $destination($passable);
        };
    }
}
