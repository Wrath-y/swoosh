<?php

namespace Src\Event;

use Src\Support\Core;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketEvent
{
    const WEBSOCKET_STATUS_CONNECTION = 1; // 连接进入等待握手
    const WEBSOCKET_STATUS_HANDSHAKE = 2; // 正在握手
    const WEBSOCKET_STATUS_FRAME = 3; // 握手成功等待浏览器发送数据帧

    private $app;

    public function __construct(Core $app)
    {
        $this->app = $app;
    }

    /**
     * Execute when open link
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\Http\Request $request
     */
    public function onOpen(Server $server, Request $request)
    {
        $server->push($request->fd, json_encode([
            'fd' => $request->fd,
            'data' => 'Start ws',
        ], JSON_UNESCAPED_SLASHES));
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\WebSocket\Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $data = json_decode($frame->data);
        if (! empty($data->target_id) && $server->connection_info($data->target_id)) {
            $server->push($data->target_id, json_encode([
                'fd' => $frame->fd,
                'data' => $data,
            ], JSON_UNESCAPED_SLASHES));
        } else {
            $server->push($frame->fd, json_encode([
                'fd' => $frame->fd,
                'data' => 'Target connection has closed',
            ], JSON_UNESCAPED_SLASHES));
        }
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $request
     * @param Swoole\WebSocket\Frame $frame
     */
    public function onClose($server, $fd)
    {
        foreach ($server->connections as $fd) {
            $server->push($fd, json_encode([
                'fd' => $fd,
                'data' => $fd . ' close',
            ]));
        }
    }
}