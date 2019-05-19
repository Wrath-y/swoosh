<?php

namespace App\Controllers\WS;

use App\Services\Service;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use App\Tasks\Chats\ChatTask;

class ChatController extends Service
{
    /**
     * @Get('/ws/chat_users')
     */
    public function fetchUserList(Server $server, $task_id, Frame $frame)
    {
        $data = $frame->data->data;
        foreach ($server->connections as $fd) {
            $server->push($fd, json_encode(success($data)));
        }
    }

    /**
     * @Post('/ws/chats')
     */
    public function pushMsg(Server $server, $task_id, Frame $frame)
    {
        $data = $frame->data->data;
        if (!empty($data->target_fd) && $server->connection_info($data->target_fd)) {
            $server->push($data->target_fd, json_encode(success($data)));
            $server->task($data, -1, [new ChatTask, 'takeNote']);
        }
    }
}
