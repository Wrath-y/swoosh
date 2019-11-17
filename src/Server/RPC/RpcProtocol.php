<?php

namespace Src\Server\RPC;

use Src\App;

class RpcProtocol
{
    /**
     * @var string
     */
    private $method = '';

    /**
     * @var string
     */
    private $proto_str = '';

    /**
     * Replace constructor
     *
     * @param string $method
     * @param string $proto_str
     *
     * @return RpcProtocol
     */
    public static function init(string $method, string $proto_str): RpcProtocol
    {
        $instance = self::getInstance();
        $instance->method = $method;
        $instance->proto_str = $proto_str;

        return $instance;
    }

    /**
     * @return RpcProtocol
     */
    public static function getInstance(): RpcProtocol
    {
        return App::get('rpc_protocol');
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getProtoStr()
    {
        return $this->proto_str;
    }
}