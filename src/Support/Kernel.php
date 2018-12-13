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

    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}
