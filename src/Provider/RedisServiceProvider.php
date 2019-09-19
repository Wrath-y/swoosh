<?php

namespace Src\Provider;

use Src\App;
use Src\Server\Redis\RedisManager;

class RedisServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('php_redis', function () {
            $config = $this->app->get('config')->get('database.redis');

            return new RedisManager($config);
        });
    }
}