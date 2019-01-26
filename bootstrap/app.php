<?php

$app = new \Src\App(dirname(__DIR__));

$app->initializeServices([
    Src\Provider\RouteTableServiceProvider::class,
    Src\Provider\DispatchServiceProvider::class,
    Src\Provider\MiddlewareProvider::class,
    Src\Provider\RedisServiceProvider::class,
    Src\Provider\DatabaseProvider::class,
    Src\Provider\PoolProvider::class,
    Src\Provider\HttpServiceProvider::class,
    Src\Provider\WebSocketServiceProvider::class,
]);

return $app;

