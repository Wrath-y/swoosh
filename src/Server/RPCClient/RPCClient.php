<?php

namespace Src\Server\RPCClient;

use Src\Server\RPCClient\Connection;
use Src\Server\RPC\Contract\EncoderInterface;

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
     * @return Connection
     */
    public function makeConnection(): Connection
    {
        $connection = Connection::init($this);
        $connection->makeConnection();

        return $connection;
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
}