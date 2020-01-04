<?php

namespace Src\Dispatcher\Provider;

use Swoole\Http\Server;
use Src\ServerEvent\HttpEvent;
use Src\Core\AbstractProvider;

class HttpServiceProvider extends AbstractProvider
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
        $this->app->set('http', function () {
            $config = $this->app->get('config')->get('app.http');
            $server = new Server($config['host'], $config['port']);
            $server->set($config['set']);
            $http = new HttpEvent($this->app);
            foreach ($this->onList as $function => $event){
                $server->on($event, [$http, $function]);
            }

            return $server;
        });
    }
}
