<?php

namespace Src\Provider;

use Src\App;
use Src\Server\Pool\RedisPool;

class PoolProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('redis_pool', function () {
            return (new RedisPool)->init()->gcSpareObject();
        });
    }
}
