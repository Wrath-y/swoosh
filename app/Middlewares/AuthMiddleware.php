<?php

namespace App\Middlewares;

use Src\Server\RequestServer;
use Src\Server\MiddlewareServer;

class AuthMiddleware extends MiddlewareServer
{
    public function handle(RequestServer $request, $next)
    {
        if ($request->request->server['request_uri'] == '/demo') {
            redirect('/demo/1');
        }

        return $next($request);
    }
}
