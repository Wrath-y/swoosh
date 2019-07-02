<?php

namespace Src\Server\Server;

use App\Kernel;
use Src\Support\Core;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Server\Task;
use Src\Server\ResponseServer;

abstract class BaseServer
{
    protected $app;

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
        $this->app->active('redis_pool');
        $this->app->active('db_pool');
    }

    /**
     * Execute when the worker stop
     *
     * @param Server $server
     * @param int $workerId
     */
    public function onWorkerStop(Server $server, int $workerId)
    {
    }

    /**
     * Execute when the browser connect
     *
     * @param Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(Server $server, int $fd, int $reactorId)
    {
    }

    /**
     * Execute when the browser close
     *
     * @param Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(Server $server, int $fd, int $reactorId)
    {
    }

    /**
     * @param Server $server
     * @param int $taskId
     * @param int $srcWorkerId
     * @param array $data
     * @return string
     */
    public function onTask(Server $server, Task $task)
    {
        $task->finish($task->data);
    }

    /**
     * Execute when $server->finish($data) on onTask
     * @param Server $server
     * @param int $taskId
     * @param $data
     * @return mixed
     */
    public function onFinish(Server $server, int $task_id, $data)
    {
    }
}