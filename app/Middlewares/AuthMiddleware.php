<?php

namespace App\Middlewares;

use Src\Server\RequestServer;
use Src\Server\Middleware\MiddlewareServer;
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
