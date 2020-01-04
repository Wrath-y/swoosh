<?php

namespace Src\RPCServer;

use Src\App;
use Src\RPCServer\Connections\ConsulConnection;

class RPCServerManager
{
    /**
     * Service discovery driver
     */
    protected $driver;

    /**
     * Witch register center will be used
     */
    public function getConnection()
    {
        $config = $this->getRpcServerConfig();
        switch ($config['driver']) {
            case 'consul':
                return new ConsulConnection;
                break;
        }

        throw new \Exception('Unsupported driver [' . $config['driver'] . ']');
    }

    public function getDriver()
    {
        if (!$this->driver) {
            $this->driver = $this->getConnection();
        }
        return $this->driver;
    }

    public function getRpcServerConfig()
    {
        return App::get('config')->get('app.rpc_server');
    }

    public function __call($method, $parameters)
    {
        return $this->getDriver()->$method(...$parameters);
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }
}