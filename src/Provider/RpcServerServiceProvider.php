<?php

namespace Src\Provider;

use Swoole\Http\Server;
use Src\Event\RpcServerEvent;
use Src\Server\RPC\RPCServerManager;

class RpcServerServiceProvider extends AbstractProvider
{
    protected $onList = [
        'onRequest'=>'Request',
        'onStart'=>'Start',
        'onShutdown'=>'Shutdown',
        'onWorkerStart'=>'WorkerStart',
        'onWorkerStop'=>'WorkerStop',
        'onConnect'=>'Connect',
        'onClose'=>'Close',
        'onTask'=>'Task',
        'onFinish'=>'Finish',
    ];

    public function register()
    {
        $this->app->set('rpc', function () {
            return new RPCServerManager;
        });

        $this->app->set('rpc_server', function () {
            $this->app->get('rpc')->register();
            $config = $this->app->get('config')->get('app.rpc_server');
            $server = new Server($config['host'], $config['port']);
            $server->set($config['set']);
            $http = new RpcServerEvent($this->app);
            foreach ($this->onList as $function => $event){
                $server->on($event, [$http, $function]);
            }

            return $server;
        });
    }
}