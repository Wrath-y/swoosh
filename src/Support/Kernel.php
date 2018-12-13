<?php

namespace Src\Support;


class Kernel
{
    /**
     * Global middleware, middleware for all routes
     *
     * @var array
     */
    protected $middleware;

    protected $routeMiddleware;

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function getRouteMiddleware(string $key): string
    {
        return isset($this->routeMiddleware[$key]) ? $this->routeMiddleware[$key] : '';
    }
}
