<?php

$app = new \Src\App(dirname(__DIR__));

$app->initializeServices([
    Src\Provider\LogServiceProvider::class,
    Src\Provider\RouteTableServiceProvider::class,
    Src\Provider\DispatchServiceProvider::class,
    Src\Provider\MiddlewareServerProvider::class,
    // Src\Provider\RedisServiceProvider::class,
    // Src\Provider\DatabaseServerProvider::class,
    // Src\Provider\PoolServerProvider::class,
    Src\Provider\HttpServiceProvider::class,
    Src\Provider\WebSocketServiceProvider::class,
    Src\Provider\RpcServiceProvider::class,
    Src\Provider\RpcServerServiceProvider::class,
    Src\Provider\RpcClientServiceProvider::class
]);

return $app;

