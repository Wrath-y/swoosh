<?php

namespace Src\RPC\Provider;

use Src\RPC\RpcProtocol;
use Src\Core\AbstractProvider;
use Src\RPCClient\RPCClient;
use Src\RPCServer\RPCServerManager;
use Src\RPCClient\ConnectionFactory;

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
            return (new ConnectionFactory)->createConnection($this->app->get('config')->get('app.rpc_client'));
        });
    }
}