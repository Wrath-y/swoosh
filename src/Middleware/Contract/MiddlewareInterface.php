<?php

namespace Src\Middleware\Contract;

use Src\Dispatcher\RequestServer;


interface MiddlewareInterface
{
    public function handle(RequestServer $request, $next);
}