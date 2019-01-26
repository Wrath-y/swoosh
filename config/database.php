<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
         'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'blog'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ],
    'redis' => [
        'client' => 'swredis',
        'mode' => 'pool',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'timeout' => env('REDIS_TIMEOUT', 1.5),
            'password' => env('REDIS_PASSWORD', null),
            'database' => 0,
        ],
    ],
];