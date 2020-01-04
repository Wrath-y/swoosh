<?php

namespace Src\Redis;

use Src\App;
use Src\Core\Contexts\RedisContext;

class RedisManager
{
    /**
     * The name of the default driver.
     *
     * @var string
     */
    protected $driver;

    /**
     * The Redis server configurations.
     *
     * @var array
     */
    protected $config;

    /**
     * The Redis connections.
     *
     * @var mixed
     */
    protected $connections;

    protected $is_pool = false;

    /**
     * Create a new Redis manager instance.
     *
     * @param  string  $driver
     * @param  array  $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->is_pool = $config['mode'] === 'pool';
        $this->config = $config;
        $this->driver = $config['client'];
    }

    /**
     * Get a Redis connection by name.
     *
     * @param  string|null  $name
     */
    public function connection($name = null)
    {
        $name = $name ? : 'default';

        if (isset($this->connections[$name]) && ! $this->is_pool) {
            return $this->connections[$name];
        }

        return $this->connections[$name] = $this->resolve($name);
    }

    /**
     * Resolve the given connection by name.
     *
     * @param  string|null  $name
     *
     * @throws \Exception
     */
    public function resolve($name = null)
    {
        $name = $name ? : 'default';

        if (isset($this->config[$name])) {
            return $this->connector()->connect($this->config[$name]);
        }

        throw new \Exception(
            "Redis connection [{$name}] not configured."
        );
    }

    /**
     * Get the connector instance for the current driver.
     *
     * @return \Src\Redis\Connectors\SwRedisConnector || \Src\Redis\Connectors\HRedisConnector
     */
    protected function connector()
    {
        switch ($this->driver) {
            case 'hredis':
                return new Connectors\HRedisConnector;
        }
    }

    /**
     * Pass methods onto the default Redis connection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($this->is_pool) {
            $obj = App::get('redis_pool')->getConnection();

            if (!$obj) {
                return false;
            }
            RedisContext::set(function () use ($obj, $method, $parameters) {
                return $obj['db']->{$method}(...$parameters);
            });
            $result = RedisContext::get();
            App::get('redis_pool')->push($obj);

            return $result;
        }

        return $this->connection()->{$method}(...$parameters);
    }
}
