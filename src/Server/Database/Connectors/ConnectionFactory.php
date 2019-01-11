<?php

namespace Src\Server\Database\Connectors;

use Src\Support\Core;
use Src\Server\Database\Connections\Connection;
use Src\Server\Database\Connections\MySqlConnection;

class ConnectionFactory
{
    /**
     * The Core instance.
     *
     * @var \Src\Support\Core
     */
    protected $app;

    public function __construct(Core &$app)
    {
        $this->app = $app;
    }

    public function make(array $config)
    {
        return $this->createSingleConnection($config);
    }

    protected function createSingleConnection(array $config)
    {
        $pdo = $this->createPdoResolver($config);

        return $this->createConnection(
            $config['driver'],
            $pdo,
            $config['database'],
            $config['prefix'],
            $config
        );
    }

    protected function createPdoResolver(array $config)
    {
        return function () use ($config) {
            foreach ($this->parseHosts($config) as $host) {
                $config['host'] = $host;

                try {
                    return $this->createConnector($config)->connect($config);
                } catch (PDOException $e) {
                    throw $e->getMessage();
                }
            }

            throw $e;
        };
    }

    protected function parseHosts(array $config)
    {
        $hosts = !is_array($config['host']) ? [$config['host']] : $config['host'];

        if (empty($hosts)) {
            throw new \Exception('Database hosts array is empty.');
        }

        return $hosts;
    }

    /**
     * Create a connector instance based on the configuration.
     *
     * @param  array  $config
     * @return \Src\Server\Database\Connectors\ConnectorInterface
     */
    public function createConnector(array $config)
    {
        if (!isset($config['driver'])) {
            throw new \Exception('A driver must be specified.');
        }

        switch ($config['driver']) {
            case 'mysql':
                return new MySqlConnector;
        }

        throw new \Exception("Unsupported driver [{$config['driver']}]");
    }

    /**
     * Create a new connection instance.
     *
     * @param  string   $driver
     * @param  \PDO|\Closure     $connection
     * @param  string   $database
     * @param  string   $prefix
     * @param  array    $config
     * @return \Src\Server\Database\Connection
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($resolver = Connection::getResolver($driver)) {
            return $resolver($connection, $database, $prefix, $config);
        }

        switch ($driver) {
            case 'mysql':
                return new MySqlConnection($connection, $database, $prefix, $config);
        }

        throw new Exception("Unsupported driver [$driver]");
    }
}
