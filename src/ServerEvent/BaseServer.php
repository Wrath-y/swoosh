<?php

namespace Src\ServerEvent;

use App\Kernel;
use Src\Core\App;
use Swoole\Server\Task;

abstract class BaseServer
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Execute when the server start
     *
     * @param Server $server Which server is started is the server
     */
    public function onStart($server)
    {
    }

    /**
     * Execute when the server close
     *
     * @param Server $server Which server is started is the server
     */
    public function onShutdown($server)
    {
    }

    /**
     * Execute when the worker start
     *
     * @param Server $server Which server is started is the server
     */
    public function onWorkerStart($server)
    {
        $kernel = new Kernel($this->app);
        $kernel->bootstrap();
        if ($this->app->get('redis_pool')) {
            $this->app->active('redis_pool');
        }
        if ($this->app->get('db_pool')) {
            $this->app->active('db_pool');
        }
    }

    /**
     * Execute when the worker stop
     *
     * @param Server $server Which server is started is the server
     * @param int $workerId
     */
    public function onWorkerStop($server, int $workerId)
    {
    }

    /**
     * Execute when the browser connect
     *
     * @param Server $server Which server is started is the server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect($server, int $fd, int $reactorId)
    {
    }

    /**
     * Execute when the browser close
     *
     * @param Server $server Which server is started is the server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose($server, int $fd, int $reactorId)
    {
    }

    /**
     * @param Server $server Which server is started is the server
     * @param int $taskId
     * @param int $srcWorkerId
     * @param array $data
     * @return string
     */
    public function onTask($server, Task $task)
    {
        $task->finish($task->data);
    }

    /**
     * Execute when $server->finish($data) on onTask
     * @param Server $server Which server is started is the server
     * @param int $taskId
     * @param $data
     * @return mixed
     */
    public function onFinish($server, int $task_id, $data)
    {
    }
}