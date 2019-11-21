<?php

namespace Src\Server\RPC\Packet;

use Src\Server\RPC\RpcProtocol;
use Src\Server\RPC\Contract\EncoderInterface;

class Encoder implements EncoderInterface
{
    public static function rpcEncode(RpcProtocol $protocol): string
    {
        return $protocol->getMethod() . "\r\n" . $protocol->getProtoClassName() . "\r\n" . $protocol->getProtoStr();
    }

    public static function rpcDecode(string $str): RpcProtocol
    {
        return RpcProtocol::init(...explode("\r\n", $str));
    }
}