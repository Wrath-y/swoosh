<?php

namespace App\Services;

class ChatRedisService extends Service
{
    public static function userList()
    {
        return \PHPRedis::hvals('chat_users');
    }

    public static function set($data)
    {
        return \PHPRedis::hset('chat_users', $data['name'], json_encode($data));
    }

    public static function delete($name)
    {
        return \PHPRedis::hdel('chat_users', $name);
    }
}
