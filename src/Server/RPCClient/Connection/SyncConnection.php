<?php

namespace Src\Server\RPCClient\Connection;

use Swoole\Coroutine\Client;
use Src\Server\RPC\RpcProtocol;
use Src\Server\RPC\Packet\Encoder;

class SyncConnection extends Connection
{
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
     * @return array
     */
    public function recv(): array
    {
        $res = $this->connection->recv();

        if ($res === false) {
            throw new \Exception('获取数据失败', $this->connection->errCode);
        }

        $res = unpack("A7A{$res[0]}A5A{$res[1]}A5A*", $res);

        return $res;
    }
}