<?php

namespace Src\Provider;

use Src\Server\RPC\RpcProtocol;
use Src\Server\RPCClient\Connection;
use Src\Server\RPCClient\RPCClient;
use Src\Server\RPCServer\RPCServerManager;

class RpcServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('rpc', function () {
            return new RPCServerManager;
        });

        $this->app->set('rpc_protocol', function () {
            return new RpcProtocol;
        });

        $this->app->set('rpc_client', function () {
            return new RPCClient;
        });

        $this->app->set('rpc_connection', function () {
            return new Connection;
        });
    }
}