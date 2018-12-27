<?php

namespace Src\Server\Redis;

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

    /**
     * Create a new Redis manager instance.
     *
     * @param  string  $driver
     * @param  array  $config
     * @return void
     */
    public function __construct(array $config)
    {
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

        if (isset($this->connections[$name])) {
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
     * @return \Src\Server\Redis\Connectors\SwRedisConnector || \Src\Server\Redis\Connectors\HRedisConnector
     */
    protected function connector()
    {
        switch ($this->driver) {
            case 'phpredis':
                return new Connectors\SwRedisConnector;
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
        return $this->connection()->{$method}(...$parameters);
    }
}
