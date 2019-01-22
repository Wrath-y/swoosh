<?php

namespace App\Services;

class ChatService extends Service
{
    public function userList()
    {
        return \PHPRedis::hvals('userss');
    }

    public function set($data)
    {
        return \PHPRedis::hset('userss', $data['name'], json_encode($data));
    }

    public function delete($name)
    {
        return \PHPRedis::hdel('userss', $name);
    }
}
