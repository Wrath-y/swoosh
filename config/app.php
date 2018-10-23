<?php

return [
    'server'    => [
        'host' => env('SERVER_HOST', '127.0.0.1'),
        'port' => env('SERVER_PORT', '8081'),
    ],
    'bootScan'  => [
        'App\Controllers\Admin',
        'App\Controllers\API',
    ],
];
