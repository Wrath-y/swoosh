<?php

namespace Src\Provider;

use Src\App;
use Swoole\Http\Server;
use Src\Event\HttpEvent;

class HttpServiceProvider extends AbstractProvider
{
    protected $serviceName = 'http';

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
        $this->app->set($this->serviceName, function () {
            $config = App::getSupport()->get('config')->get('server');
            $server = new Server($config['host'], $config['port']);
            $http = new HttpEvent();
            foreach ($this->onList as $function => $event){
                $server->on($event, [$http, $function]);
            }

            return $server;
        });
    }
}
