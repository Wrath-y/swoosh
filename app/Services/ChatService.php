<?php

namespace App\Services;

use Swoole\WebSocket\Server;
use App\Models\ChatLog;

class ChatService extends Service
{
    public function fetchUserList(Server $server, $task_id, $data)
    {
        foreach ($server->connections as $fd) {
            $server->push($fd, $data);
        }
    }

    public function takeNote(Server $server, $task_id, $data)
    {
        go(function () use ($data) {
            ChatLog::insert([
                'source_name' => $data->source_name,
                'target_name' => $data->target_name,
                'data' => $data->message,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        });
    }
}
