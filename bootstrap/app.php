<?php

$app = new \Src\App(dirname(__DIR__));

$app->initializeServices([
    \Src\Provider\HttpServiceProvider::class,
]);

return $app;

