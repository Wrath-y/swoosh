<?php

$app = new \Src\App(dirname(__DIR__));

$app->initializeServices([
    Src\Provider\RouteTableServiceProvider::class,
    Src\Provider\DispatchServiceProvider::class,
    Src\Provider\MiddlewareProvider::class,
    Src\Provider\HttpServiceProvider::class,
    Src\Provider\RequestProvider::class,
    Src\Provider\ResponseServerProvider::class,
    Src\Provider\RedisServiceProvider::class,
]);

return $app;

