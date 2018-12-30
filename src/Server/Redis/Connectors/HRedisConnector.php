<?php

namespace Src\Server\Redis\Connectors;

use Redis;
use Src\Server\Redis\Connections\HRedisConnection;
use Src\Server\Redis\Connections\HRedisClusterConnection;

class HRedisConnector
{
    /**
     * Create a new clustered PhpRedis connection.
     *
     * @param  array  $config
     * @return \Src\Server\Redis\Connections\GRedisConnection
     */
    public function connect(array $config)
    {
        return (new HRedisConnection($this->createClient($config)))->client();
    }

    /**
     * Create the Redis client instance.
     *
     * @param  array  $config
     * @return \Redis
     */
    protected function createClient(array $config)
    {
        $redis = new Redis();
        $redis->connect($config['host'], $config['port']);

        return $redis;
    }
}
