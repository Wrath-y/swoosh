<?php

namespace Src\Server;

use Swoole\Http\Request;
use Swoole\Http\Response;

class HttpServer extends \swoole_http_server
{
    public function __construct($host, $port, $mode = SWOOLE_PROCESS, $sock_type = SWOOLE_SOCK_TCP)
    {
        parent::__construct($host, $port, $mode, $sock_type);
        $this->on('request', function (Request $request, Response $response) {
            $response->end('OK');
        });
    }
}
