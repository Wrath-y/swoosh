<?php

namespace Src\Provider;

class RpcClientServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('rpc_client', function () {
            
        });
    }
}