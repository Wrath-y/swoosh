<?php

namespace Src\Server\RPCClient;

use Src\Server\RPCClient\Connection\CoConnection;
use Src\Server\RPCClient\Connection\SyncConnection;
use Src\Server\RPCClient\Contract\ConnectionInterface;

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