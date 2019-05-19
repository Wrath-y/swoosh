<?php
namespace App\Tasks\Chats;

use Swoole\WebSocket\Server;
use App\Models\ChatLog;

class ChatTask {
    public function takeNote(Server $server, $task_id, $data)
    {
        go(function () use ($data) {
            ChatLog::insert([
                'source_name' => $data->source_name,
                'target_name' => $data->target_name,
                'message' => $data->message,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        });
    }
}