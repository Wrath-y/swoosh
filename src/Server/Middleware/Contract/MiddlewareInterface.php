<?php

namespace Src\Server\Middleware\Contract;

use Src\Server\RequestServer;


interface MiddlewareInterface
{
    public function handle(RequestServer $request, $next);
}