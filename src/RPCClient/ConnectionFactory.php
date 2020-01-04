<?php

namespace Src\RPCClient;

use Src\RPCClient\Connection\CoConnection;
use Src\RPCClient\Connection\SyncConnection;
use Src\RPCClient\Contract\ConnectionInterface;

class ConnectionFactory
{
    /**
     * Create a connection instance based on the configuration.
     *
     * @param array $config
     * @return ConnectionInterface
     */
    public function createConnection(array $config)
    {
        if (!isset($config['driver'])) {
            throw new \Exception('A driver must be specified.');
        }

        switch ($config['driver']) {
            case 'co':
                return new CoConnection;
            case 'sync':
                return new SyncConnection;
        }

        throw new \Exception("Unsupported driver [{$config['driver']}]");
    }
}