<?php

namespace Src\Server\RPCServer\Connections;

use Src\Server\RPCServer\Contract\ConnectionInterface;

class Connection implements ConnectionInterface
{
    /**
     * @var string The host of this service
     */
    protected $host = '127.0.0.1';

    /**
     * @var int The port of this service
     */
    protected $port = 9527;
}