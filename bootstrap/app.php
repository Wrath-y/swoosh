<?php

$app = new swoole_http_server('127.0.0.1', 8081);

$app->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
    $response->end("hello world");
});

return $app;
