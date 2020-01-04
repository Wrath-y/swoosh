<?php

namespace Src\RPC\Packet;

use Src\RPC\RpcProtocol;
use Src\RPC\Contract\EncoderInterface;

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