<?php

namespace Src\Pool\Provider;

use Src\Pool\DBPool;
use Src\Pool\RedisPool;
use Src\Core\AbstractProvider;

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
