<?php

namespace Src\RPCClient\Connection;

use Src\RPC\RpcProtocol;
use Src\RPC\Packet\Encoder;
use Src\RPCClient\RPCClient;
use Src\RPCClient\Contract\ConnectionInterface;

abstract class Connection implements ConnectionInterface
{
/**
     * @var Client
     */
    protected $connection;

    /**
     * @var RPCClient
     */
    protected $client;

    /**
     * @param RPCClient $client
     * @return Connection
     */
    public function init(RPCClient $client): Connection
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param string $service The name of service
     * @param $proto The data of send to server
     *
     * @return bool
     */
    public function send(string $service, $proto = ''): bool
    {
        $rpcProtocol = Encoder::rpcEncode(
            RpcProtocol::init($service, '\\' . get_class($proto), $proto instanceof \Google\Protobuf\Internal\Message ? $proto->serializeToString() : '')
        );

        $res = $this->connection->send($rpcProtocol);

        if (!$res) {
            throw new \Exception($this->connection->errCode);
        }

        return (bool)$res;
    }
}