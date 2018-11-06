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
        App::getSupport('request')->set($request);
        preg_match('/\d+/i', $request->server['request_uri'], $params);

        return call_user_func_array([$route['controller'], $route['method']], $params);
    }
}
