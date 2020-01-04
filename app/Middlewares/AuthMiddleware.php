<?php

namespace App\Middlewares;

use Src\Dispatcher\RequestServer;
use Src\Middleware\MiddlewareServer;
use Src\Helper\JWTHelper;

class AuthMiddleware extends MiddlewareServer
{
    public function handle(RequestServer $request, $next)
    {
        if (! JWTHelper::verifyToken(request('token'))) {
            redirect('/');
        }

        return $next($request);
    }
}
