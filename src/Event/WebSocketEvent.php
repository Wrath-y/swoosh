<?php

namespace Src\Event;

use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Src\Server\Server\BaseServer;

class WebSocketEvent extends BaseServer
{
    /**
     * Execute when open link
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\Http\Request $request
     */
    public function onOpen(Server $server, Request $request)
    {
        $server->push($request->fd, json_encode(success([
            'fd' => $request->fd
        ])));
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\WebSocket\Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        go(function () use ($server, $frame) {
            $dispatcher = $this->app->get('dispatcher');
            $dispatcher->wsHandle($server, $frame);
        });
    }
}