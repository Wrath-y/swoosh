<?php

return [
    'bootScan'  => [
        'App\Controllers\Admin',
        'App\Controllers\API',
    ],
    'routeTable' => [
        'size' => env('SIZE', 8192),
    ],
    'aliases' => [
        'PHPRedis' => Src\Support\Facades\Redis::class,
        'DB' => Src\Support\Facades\Database::class,
    ],
    'http' => [
        'host' => env('HTTP_HOST', '127.0.0.1'),
        'port' => env('HTTP_PORT', '8081'),
        'daemonize' => env('HTTP_DAEMONIZE', 0),
    ],
    'ws' => [
        'host' => env('WS_HOST', '127.0.0.1'),
        'port' => env('WS_PORT', '9501'),
        'daemonize' => env('WS_DAEMONIZE', 0),
    ],
];
