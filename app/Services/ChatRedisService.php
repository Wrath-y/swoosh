<?php

namespace App\Services;

class ChatRedisService extends Service
{
    public function userList()
    {
        return \PHPRedis::hvals('chat_users');
    }

    public function set($data)
    {
        return \PHPRedis::hset('chat_users', $data['name'], json_encode($data));
    }

    public function delete($name)
    {
        return \PHPRedis::hdel('chat_users', $name);
    }
}
