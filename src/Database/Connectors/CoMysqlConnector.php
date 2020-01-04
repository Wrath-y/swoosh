<?php

namespace Src\Database\Connectors;

use Swoole\Coroutine\Mysql;

class CoMySqlConnector extends Connector
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config)
    {
        $connection = new Mysql();
        $connection->connect([
            'host' => $config['host'],
            'port' => $config['port'],
            'user' => $config['username'],
            'password' => $config['password'],
            'database' => $config['database'],
            'fetch_mode' => true
        ]);

        return $connection;
    }
}
