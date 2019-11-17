<?php

namespace Src\Server\RPCClient;

use Src\App;
use Swoole\Coroutine\Client;
use Src\Server\RPC\RpcProtocol;
use Src\Server\RPC\Packet\Encoder;
use Src\Server\RPCClient\RPCClient;
use Src\Server\RPCClient\Contract\ConnectionInterface;

class Connection implements ConnectionInterface
{
    /**
     * @var Client
     */
    private $connection;

    /**
     * @var RPCClient
     */
    private $client;

    /**
     * @param RPCClient $client
     * @return Connection
     */
    public static function init(RPCClient $client): Connection
    {
        $connection = App::get('rpc_connection');
        $connection->client = $client;

        return $connection;
    }

    public function makeConnection()
    {
        $connection = new Client(SWOOLE_SOCK_TCP);

        $connection->set($this->client->getSetting());
        if (!$connection->connect($this->client->getHost(), $this->client->getPort())) {
            throw new \Exception('rpc client连接'.$this->client->getHost().':'.$this->client->getPort().'失败');
        }

        $this->connection = $connection;
    }

    public function close()
    {
        $this->connection->close();
    }

    /**
     * @param string $service The name of service
     * @param $proto The data of send to server
     *
     * @return bool
     */
    public function send(string $service, $proto): bool
    {
        $rpc_protocol = Encoder::rpc_encode(
            RpcProtocol::init($service, $proto->serializeToString())
        );
        $res = $this->connection->send($rpc_protocol);

        if (!$res) {
            throw new \Exception($this->connection->errCode);
        }

        return (bool)$res;
    }

    /**
     * @return string
     */
    public function recv(): string
    {
        $res = $this->connection->recv((float)2);

        if ($res === false) {
            throw new \Exception($this->connection->errCode);
        }

        if ($res === '') {
            $this->close();
        }

        return $res;
    }
}