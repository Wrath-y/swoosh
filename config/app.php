<?php

return [
    'mode' => env('MODE', 'pool'),
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
    'ws' => [
        'host' => env('WS_HOST', '127.0.0.1'),
        'port' => env('WS_PORT', '8081'),
        'daemonize' => env('WS_DAEMONIZE', 0),
    ],
];
