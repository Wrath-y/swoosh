<?php

namespace App\Services;

use Swoole\WebSocket\Server;

class ChatService extends Service
{
    public function fetchUserList(Server $server, $task_id, $data)
    {
        foreach ($server->connections as $fd) {
            $server->push($fd, $data);
        }
    }
}
