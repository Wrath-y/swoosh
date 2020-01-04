<?php

namespace Src\Database\Connectors;

use PDOException;
use Src\App;
use Src\Database\Connections\Connection;
use Src\Database\Connections\MySqlConnection;

class ConnectionFactory
{
    public function make(array $config)
    {
        return $this->getConnection($config);
    }

    public function makeSinglePdo(array $config)
    {
        return $this->createPdoResolver($config)();
    }

    protected function getConnection(array $config)
    {
        if ($config['mode'] === 'pool') {
            $pdo = App::get('db_pool')->getConnection();
        } else {
            $pdo = $this->createPdoResolver($config);
        }

        return $this->createConnection(
            $config,
            $pdo
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
                    throw $e;
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
     * @return \Src\Database\Connectors\ConnectorInterface
     */
    public function createConnector(array $config)
    {
        if (!isset($config['driver'])) {
            throw new \Exception('A driver must be specified.');
        }

        switch ($config['driver']) {
            case 'co_mysql':
                return new CoMySqlConnector;
            case 'mysql':
                return new MySqlConnector;
        }

        throw new \Exception("Unsupported driver [{$config['driver']}]");
    }

    /**
     * Create a new connection instance.
     *
     * @param  string   $config['driver']
     * @param  \PDO|\Closure     $connection
     * @param  string   $config['database']
     * @param  string   $config['prefix']
     * @return \Src\Database\Connection
     */
    protected function createConnection($config, $connection)
    {
        if ($resolver = Connection::getResolver($config['driver'])) {
            return $resolver($connection, $config['database'], $config['prefix']);
        }

        switch ($config['driver']) {
            case 'co_mysql':
            case 'mysql':
                return new MySqlConnection($connection, $config, $config['database'], $config['prefix']);
        }

        throw new \Exception('Unsupported driver [' . $config['driver'] . ']');
    }
}
