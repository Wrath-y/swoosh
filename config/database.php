<?php

return [
    'redis' => [
        'client' => 'swredis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'timeout' => env('REDIS_TIMEOUT', 1.5),
            'password' => env('REDIS_PASSWORD', null),
            'database' => 0,
        ],
    ],
];