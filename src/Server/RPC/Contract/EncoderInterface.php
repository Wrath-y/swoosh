<?php

namespace Src\Server\RPC\Contract;

use Src\Server\RPC\RpcProtocol;

interface EncoderInterface
{
    /**
     * @param RpcProtocol $protocol
     *
     * @return string
     */
    public static function rpcEncode(RpcProtocol $protocol): string;

    /**
     * @param string $str
     *
     * @return RpcProtocol
     */
    public static function rpcDecode(string $str): RpcProtocol;
}