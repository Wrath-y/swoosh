<?php

namespace Src\Server\Database;

use Src\App;
use Src\Support\Core;
use Src\Server\Database\Connections\Connection;
use Src\Server\Database\Eloquent\ConnectionResolverInterface;

class DatabaseManager implements ConnectionResolverInterface
{
    protected $config = [];
    protected $is_pool = false;
    /**
     * The database connection factory instance.
     *
     * @var \Src\Server\Connectors\ConnectionFactory
     */
    protected $factory;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->is_pool = $config['mode'] === 'pool';
        $this->factory = App::get('db.factory');
    }

    public function connection($name = null)
    {
        $database = $this->parseConnectionName($name);

        if (isset($this->connections[$name]) && ! $this->is_pool) {
            return $this->connections[$name];
        }

        return $this->connections[$name] = $this->configure(
            $this->makeConnection($database)
        );
    }

    /**
     * Parse the connection into an array of the name
     *
     * @param  string  $name
     * @return array
     */
    protected function parseConnectionName($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        return $name;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return App::get('config')->get('database.default');
    }

    /**
     * Make the database connection instance.
     *
     * @param  string  $name
     * @return \Src\Server\Database\Connection
     */
    protected function makeConnection($name)
    {
        $config = $this->configuration($name);

        // First we will check by the connection name to see if an extension has been
        // registered specifically for that connection. If it has we will call the
        // Closure and pass it the config allowing it to resolve the connection.
        if (isset($this->extensions[$name])) {
            return call_user_func($this->extensions[$name], $config, $name);
        }

        // Next we will check to see if an extension has been registered for a driver
        // and will call the Closure if so, which allows us to have a more generic
        // resolver for the drivers themselves which applies to all connections.
        if (isset($this->extensions[$driver = $config['driver']])) {
            return call_user_func($this->extensions[$driver], $config, $name);
        }

        return $this->factory->make($config);
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     */
    protected function configuration($name)
    {
        $name = $name ? : $this->getDefaultConnection();

        $connections = App::get('config')->get('database.connections');

        if (is_null($config = ($connections[$name] ?? null))) {
            throw new \Exception("Database [$name] not configured.");
        }

        return $config;
    }

    protected function configure(Connection $connection)
    {
        $connection->setReconnector(function ($connection) {
            $this->reconnect($connection->getName());
        });

        return $connection;
    }

    public function reconnect($name = null)
    {
        $this->disconnect($name = $name ? : $this->getDefaultConnection());

        if (!isset($this->connections[$name])) {
            return $this->connection($name);
        }

        return $this->refreshPdoConnections($name);
    }

    public function disconnect($name = null)
    {
        if (isset($this->connections[$name = $name ? : $this->getDefaultConnection()])) {
            $this->connections[$name]->disconnect();
        }
    }

    /**
     * Refresh the PDO connections on a given connection.
     *
     * @param  string  $name
     * @return \Src\Server\Database\Connection
     */
    protected function refreshPdoConnections($name)
    {
        $fresh = $this->makeConnection($name);

        return $this->connections[$name]
            ->setPdo($fresh->getPdo());
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        switch ($this->is_pool) {
            case true:
                $obj = App::get('db_pool')->getConnection();
                if (!$obj) {
                    return false;
                }
                DBContext::set($this->connection()->$method(...$parameters));
                App::get('db_pool')->push($obj);

                return DBContext::get();
            default:
                return $this->connection()->$method(...$parameters);
        }
    }
}
