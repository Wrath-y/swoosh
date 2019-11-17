<?php

namespace Src\Server\Middleware;

use Src\Server\RequestServer;
use Src\Server\Middleware\Contract\MiddlewareInterface;

abstract class MiddlewareServer implements MiddlewareInterface
{
    protected $request;

    abstract function handle(RequestServer $request, $next);
}
