<?php

namespace Src\Middleware;

use Src\Dispatcher\RequestServer;
use Src\Middleware\Contract\MiddlewareInterface;

abstract class MiddlewareServer implements MiddlewareInterface
{
    protected $request;

    abstract function handle(RequestServer $request, $next);
}
