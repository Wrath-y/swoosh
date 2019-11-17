<?php

namespace App\Moddlewares;

use Src\Server\RequestServer;
use Src\Server\Middleware\MiddlewareServer;

class AuthMiddleware extends MiddlewareServer
{
    public function handle(RequestServer $request, $next)
    {
        print_r('this is auth');

        $next($request);
    }
}
