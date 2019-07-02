<?php

return [
    'mode' => env('MODE', 'pool'),
    'bootScan'  => [
        'App\Controllers\Admin',
        'App\Controllers\API',
        'App\Controllers\WS',
    ],
    'route_table' => [
        'size' => env('SIZE', 8192),
    ],
    'aliases' => [
        'PHPRedis' => Src\Support\Facades\Redis::class,
        'DB' => Src\Support\Facades\Database::class,
    ],
    'http' => [
        'host' => env('HTTP_HOST', '127.0.0.1'),
        'port' => env('HTTP_PORT', 8081),
        'set' => [
            'worker_num' => env('HTTP_WORKER_NUM', 2),
            'max_request' => env('HTTP_MAX_REQUEST', 10),
            'task_worker_num' => env('HTTP_TASK_WORKER_NUM', 2),
            'daemonize' => env('HTTP_DAEMONIZE', 0),
            'task_enable_coroutine' => true,
        ],
    ],
    'ws' => [
        'host' => env('WS_HOST', '127.0.0.1'),
        'port' => env('WS_PORT', 9501),
        'set' => [
            'worker_num' => env('WS_WORKER_NUM', 1),
            'max_request' => env('WS_MAX_REQUEST', 10),
            'task_worker_num' => env('WS_TASK_WORKER_NUM', 2),
            'daemonize' => env('WS_DAEMONIZE', 0),
            'task_enable_coroutine' => true,
        ],
    ],
    'rpc_server' => [
        'host' => env('RPC_HOST', '127.0.0.1'),
        'port' => env('RPC_PORT', 9527),
        'set' => [
            'worker_num' => env('RPC_WORKER_NUM', 1),
            'max_request' => env('RPC_MAX_REQUEST', 10),
            'task_worker_num' => env('RPC_TASK_WORKER_NUM', 2),
            'daemonize' => env('RPC_DAEMONIZE', 0),
            'task_enable_coroutine' => true,
        ],
    ],
];
