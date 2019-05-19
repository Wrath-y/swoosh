<?php

namespace Src\Provider;

use Src\App;
use Src\Server\Pool\DBPool;
use Src\Server\Pool\RedisPool;

class PoolServerProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('redis_pool', function () {
            return (new RedisPool)->init()->gcSpareObject();
        });
        $this->app->set('db_pool', function () {
            return (new DBPool)->init()->gcSpareObject();
        });
    }
}
