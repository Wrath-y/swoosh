<?php

$app = new Src\App(dirname(__DIR__));

$app->initializeServices([
    Src\Log\Provider\LogServiceProvider::class,
    Src\Dispatcher\Provider\RouteTableServiceProvider::class,
    Src\Dispatcher\Provider\DispatchServiceProvider::class,
    Src\Middleware\Provider\MiddlewareServerProvider::class,
    // RedisServiceProvider::class,
    // DatabaseServerProvider::class,
    // PoolServerProvider::class,
    Src\Dispatcher\Provider\HttpServiceProvider::class,
    Src\Dispatcher\Provider\WebSocketServiceProvider::class,
    Src\RPC\Provider\RpcServiceProvider::class,
    Src\RPCServer\Provider\RpcServerServiceProvider::class,
    Src\RPCClient\Provider\RpcClientServiceProvider::class
]);

return $app;

