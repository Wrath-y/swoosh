<?php

namespace Src\RPC\Contract;

use Src\RPC\RpcProtocol;

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