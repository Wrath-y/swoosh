<?php

namespace Src\RPCClient;

use Src\App;
use Src\RPC\Contract\EncoderInterface;
use Src\RPCClient\Contract\ConnectionInterface;

class RPCClient
{
    /**
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * @var int
     */
    private $port = 9527;

    /**
     * Setting
     *
     * @var array
     */
    protected $setting = [];

    /**
     * @var EncoderInterface
     */
    protected $encoder;

    /**
     * @return ConnectionInterface
     */
    public function __construct()
    {
        $connection = App::get('rpc_connection');
        $connection = $connection->init($this);
        $connection->makeConnection();
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return array_merge($this->defaultSetting(), $this->setting);
    }

    /**
     * @return EncoderInterface
     */
    public function getEncoder(): EncoderInterface
    {
        return $this->encoder;
    }

    /**
     * @return array
     */
    private function defaultSetting(): array
    {
        return [
            'open_eof_check' => true,
            'open_eof_split' => true,
            'package_eof'    => "\r\n",
        ];
    }

    public function __call($method, $parameters)
    {
        return App::get('rpc_connection')->$method(...$parameters);
    }
}