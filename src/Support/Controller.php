<?php

namespace Src\Support;

use Src\Server\RequestServer;
use Src\Server\ResponseServer;

abstract class Controller
{
    /**
     * @var RequestServer
     */
    protected $request;

    /**
     * @var ResponseServer
     */
    protected $response;

    public function __construct(RequestServer &$request, ResponseServer &$response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * redirect
     *
     * @param $url
     * @return bool
     */
    public function redirect(string $url)
    {
        $this->response->header("Location", $url);
        $this->response->status(302);
        $this->response->end('');

        return true;
    }
}