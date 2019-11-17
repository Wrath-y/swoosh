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
    public static function rpc_encode(RpcProtocol $protocol): string;

    /**
     * @param string $str
     *
     * @return RpcProtocol
     */
    public static function rpc_decode(string $str): RpcProtocol;
}