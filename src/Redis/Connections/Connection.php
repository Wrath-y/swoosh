<?php

namespace Src\Redis\Connections;

class Connection
{
    /**
     * The Redis client.
     *
     * @var Swoole\Coroutine\Redis  $client
     */
    protected $client;

    /**
     * Get the underlying Redis client.
     *
     * @return mixed
     */
    public function client()
    {
        return $this->client;
    }
}
