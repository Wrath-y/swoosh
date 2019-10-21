<?php

declare(strict_types=1);

\Swoole\Runtime::enableCoroutine();

require __DIR__ . '/bootstrap/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

\Swoole\Coroutine::set([
    'max_coroutine' => 300000,
]);

$app->command($argv);
