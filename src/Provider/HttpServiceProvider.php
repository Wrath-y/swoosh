<?php

namespace Src\Provider;

use Src\App;
use Swoole\Http\Server;
use Swoole\Table;
use Src\Event\HttpEvent;

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
            $config = $this->app->get('config')->get('app.server');
            $server = new Server($config['host'], $config['port']);
            $server->set([
                'daemonize' => $config['daemonize'],
            ]);
            $http = new HttpEvent($this->app);
            foreach ($this->onList as $function => $event){
                $server->on($event, [$http, $function]);
            }

            return $server;
        });
    }
}
