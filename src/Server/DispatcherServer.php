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
        dd($middleware);
        $middleware = $middleware + 'App\\Middlewares\\' . ucfirst($route['']);
        $destination = $this->getDestination($request, $response, $controller, $route['method'], $params);

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
}
