<?php

namespace App;

use App\Middlewares\AuthMiddleware;
use Src\Support\Kernel as Base;

class Kernel extends Base
{
    /**
     * Global middleware, middleware for all routes
     *
     * @var array
     */
    protected $middleware = [
    ];

    protected $routeMiddleware = [
        'auth' => AuthMiddleware::class,
    ];
}
