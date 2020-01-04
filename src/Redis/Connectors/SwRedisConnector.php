<?php

namespace Src\Redis\Connectors;

use Swoole\Coroutine\Redis;
use Src\Redis\Connections\SwRedisConnection;
use Src\Redis\Connections\SwRedisClusterConnection;

class SwRedisConnector
{
    /**
     * Create a new clustered PhpRedis connection.
     *
     * @param  array  $config
     * @return \Src\Redis\Connections\SwRedisConnection
     */
    public function connect(array $config)
    {
        return (new SwRedisConnection($this->createClient($config)))->client();
    }

    /**
     * Create the Redis client instance.
     *
     * @param  array  $config
     * @return \Redis
     */
    protected function createClient(array $config)
    {
        $redis = new Redis($config);
        $redis->connect($config['host'], $config['port']);

        return $redis;
    }
}
