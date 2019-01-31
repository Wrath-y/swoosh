<?php

namespace Src\Event;

use Src\Support\Core;
use Src\Support\Kernel;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use App\Services\ChatService;

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
            'fd' => $request->fd
        ]));
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
        if ($data === 'fetchUserList') {
            foreach ($server->connections as $fd) {
                $server->push($fd, $frame->data);
            }
        } else if (! empty($data->fd) && $server->connection_info($data->fd)) {
            $server->push($data->fd, json_encode([
                'source_fd' => $frame->fd,
                'data' => $data,
            ]));
        } else {
            $server->push($frame->fd, json_encode([
                'data' => 'Target connection has closed',
            ]));
        }
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $server
     * @param $fd
     */
    public function onClose($server, $fd)
    {
        $user_list = ChatService::userList();
        foreach ($user_list as $user) {
            if (strpos($user, ":$fd}")) {
                $user = json_decode($user);
                ChatService::delete($user->name);
                break;
            }
        }
        $data = json_encode('fetchUserList');
        foreach ($server->connections as $f) {
            $server->push($f, $data);
        }
    }

    /**
     * @param Server $server
     * @param int $taskId
     * @param int $srcWorkerId
     * @param array $data
     * @return string
     */
    public function onTask(Server $server, \Swoole\Server\Task $task)
    {
    }

    /**
     * @param Server $server
     * @param int $taskId
     * @param $data
     * @return mixed
     */
    public function onFinish(Server $server, int $taskId, $data)
    {
    }
}