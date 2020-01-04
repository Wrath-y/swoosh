<?php

namespace Src\Core;

use Src\Dispatcher\RequestServer;
use Src\Dispatcher\ResponseServer;

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
}