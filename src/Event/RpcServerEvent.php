<?php

namespace Src\Event;

use Swoole\Server;
use Src\Server\Server\BaseServer;

class RpcServerEvent extends BaseServer
{

    /**
     * Execute when receive
     *
     * @param Server $server
     * @param int $fd
     * @param int $reactor_id
     * @param string $data
     */
    public function onReceive(Server $server, int $fd, int $reactor_id, string $data)
    {
        go(function () use ($server, $fd, $data) {
            $dispatcher = $this->app->get('dispatcher');
            $dispatcher->rpcHandle($server, $fd, $data);
        });
    }
}