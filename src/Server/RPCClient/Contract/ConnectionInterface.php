<?php

namespace Src\Server\RPCClient\Contract;

use Src\Server\RPC\Packet\Recv;

interface ConnectionInterface
{
    public function close();
    public function send(string $name, $data): bool;
    public function recv(): array;
}