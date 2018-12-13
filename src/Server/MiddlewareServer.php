<?php

namespace Src\Server;

use Src\Server\RequestServer;
use Src\Interfaces\MiddlewareInterface;

abstract class MiddlewareServer implements MiddlewareInterface
{
    protected $request;

    public function handler(RequestServer $request, $next);
}
