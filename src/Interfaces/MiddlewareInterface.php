<?php

namespace Src\Interfaces;

use Src\Server\RequestServer;


interface MiddlewareInterface
{
    public function handle(RequestServer $request, $next);
}