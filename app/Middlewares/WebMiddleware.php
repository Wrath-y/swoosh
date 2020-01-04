<?php

namespace App\Moddlewares;

use Src\Dispatcher\RequestServer;
use Src\Middleware\MiddlewareServer;

class AuthMiddleware extends MiddlewareServer
{
    public function handle(RequestServer $request, $next)
    {
        print_r('this is auth');

        $next($request);
    }
}
