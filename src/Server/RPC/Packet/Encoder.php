<?php

namespace Src\Server\RPC\Packet;

use Src\Server\RPC\RpcProtocol;
use Src\Server\RPC\Contract\EncoderInterface;

class Encoder implements EncoderInterface
{
    public static function rpc_encode(RpcProtocol $protocol): string
    {
        return $protocol->getMethod() . "\r\n" . $protocol->getProtoStr();
    }

    public static function rpc_decode(string $str): RpcProtocol
    {
        [$method, $params] = explode('\r\n', $str);

        return RpcProtocol::init($method, $params);
    }
}