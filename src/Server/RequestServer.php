<?php

namespace Src\Server;

use Src\App;
use Swoole\Http\Request;

class RequestServer
{
    private $table;

    public function __construct()
    {
        $this->table = App::getSupport('routeTableServer');
        
    }

    public function get (Request $request)
    {
        $uri = preg_replace('/\d+/i', '{}', $request->server['request_uri']);
        $method = strtolower($request->server['request_method']);
        $routes = $this->table->all();
        if (isset($routes[$method . '@' . $uri])) {
            dd(1);
        }
        dd(2);
    }
}
