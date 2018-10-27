<?php

$app = new \Src\App(dirname(__DIR__));

$app->initializeServices([
    \Src\Provider\HttpServiceProvider::class,
    \Src\Provider\RouteTableServiceProvider::class,
    \Src\Provider\DispatchServiceProvider::class,
]);

return $app;

