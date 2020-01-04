<?php

namespace Src\RPCClient\Contract;

use Src\RPC\Packet\Recv;

interface ConnectionInterface
{
    public function close();
    public function send(string $name, $data): bool;
    public function recv(): array;
}