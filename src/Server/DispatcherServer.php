<?php

namespace Src\Server;

use Src\Resource\AnnotationResource;
use Src\App;
use Swoole\Http\Request;

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

    public function handle(Request $request, $route)
    {
        $class = new \ReflectionClass($route['controller']);
        $method = $class->getMethod($route['method']);
        foreach ($method->getParameters() as $parameter) {
            $paramName = $parameter->getName();
            
        }
    }
}
