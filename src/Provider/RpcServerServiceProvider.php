<?php

namespace Src\Provider;

class RpcServerServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('rpc_server', function () {
            $config = $this->app->get('config')->get('rpc.server');

            return new RedisManager($config);
        });
    }
}