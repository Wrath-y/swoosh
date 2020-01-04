<?php

namespace Src\RPCClient\Provider;

use Src\Core\AbstractProvider;
use Src\RPCClient\RPCClient;

class RpcClientServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('rpc_client', function () {
            return new RPCClient;
        });
    }
}