<?php

$app = new \Src\App(dirname(__DIR__));

$app->initializeServices([
    \Src\Provider\RouteTableServiceProvider::class,
    \Src\Provider\DispatchServiceProvider::class,
    \Src\Provider\HttpServiceProvider::class,
]);

return $app;

