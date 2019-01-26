<?php

namespace Src\Event;

use App\Kernel;
use Src\Support\Core;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Src\Server\RequestServer;
use Src\Server\ResponseServer;

class HttpEvent
{
    private $app;

    public function __construct(Core $app)
    {
        $this->app = $app;
    }

    /**
     * Execute when requested
     *
     * @param Swoole\Http\Request $swrequest
     * @param Swoole\Http\Response $swresponse
     * @return ResponseServer
     */
    public function onRequest(Request $swrequest, Response $swresponse)
    {
        $dispatcher = $this->app->get('dispatcher');
        $request = new RequestServer($swrequest, $swresponse);
        $response = new ResponseServer($swresponse);

        $dispatcher->handle($request, $response);
    }

    /**
     * Execute when the server start
     *
     * @param Server $server
     */
    public function onStart(Server $server)
    {
    }

    /**
     * Execute when the server close
     *
     * @param Server $server
     */
    public function onShutdown(Server $server)
    {
    }

    /**
     * Execute when the worker start
     *
     * @param Server $server
     */
    public function onWorkerStart(Server $server)
    {
        $kernel = new Kernel($this->app);
        $kernel->bootstrap();
    }

    /**
     * Execute when the worker stop
     *
     * @param Server $server
     * @param int $workerId
     */
    public function onWorkerStop(Server $server,int $workerId)
    {
    }

    /**
     * Execute when the browser connect
     *
     * @param Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(Server $server,int $fd,int $reactorId)
    {
    }

    /**
     * Execute when the browser close
     *
     * @param Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(Server $server,int $fd,int $reactorId)
    {
    }

    /**
     * @param Server $server
     * @param int $taskId
     * @param int $srcWorkerId
     * @param array $data
     * @return string
     */
    public function onTask(Server $server,int $taskId,int $srcWorkerId,array $data)
    {
    }

    /**
     * @param Server $server
     * @param int $taskId
     * @param $data
     * @return mixed
     */
    public function onFinish(Server $server,int $taskId,$data)
    {
    }
}