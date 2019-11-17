<?php

namespace Src\Server\RPCClient\Contract;

interface ConnectionInterface
{
    public function close();
    public function send(string $name, $data): bool;
    public function recv(): string;
}