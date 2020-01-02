<?php

namespace Src\ServerEvent;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Src\Server\RequestServer;
use Src\Server\ResponseServer;
use Src\Server\Server\BaseServer;

class HttpEvent extends BaseServer
{
    /**
     * Execute when requested
     *
     * @param Swoole\Http\Request $swrequest
     * @param Swoole\Http\Response $swresponse
     * @return ResponseServer
     */
    public function onRequest(Request $swrequest, Response $swresponse)
    {
        go(function () use ($swrequest, $swresponse) {
            $dispatcher = $this->app->get('dispatcher');
            $request = new RequestServer($swrequest);
            $response = new ResponseServer($swresponse);
            $dispatcher->httpHandle($request, $response);
        });
    }
}