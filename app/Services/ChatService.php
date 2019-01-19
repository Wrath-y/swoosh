<?php

namespace App\Services;

class ChatService extends Service
{
    public function userList(): array
    {
        return \PHPRedis::lrange('users', 0, -1);
    }

    public function create(string $data): int
    {
        return \PHPRedis::lpush('users', $data);
    }

    public function update(string $data): int
    {
        $users = \PHPRedis::lrange('users', 0, -1);
        foreach ($users as $key => $user) {
            if ($data->name === $user->name) {
                return \PHPRedis::lset('users', $key, $data);
            }
        }

        return 0;
    }
}
