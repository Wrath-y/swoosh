<?php

namespace Src\Server\RPC\Connections;

use Src\Server\RPC\ConnectionInterface;

class Connection implements ConnectionInterface
{
    /**
     * The address of remote server.
     *
     * @var string
     */
    protected $ip;

    /**
     * The port of remote server.
     *
     * @var string
     */
    protected $port;
}