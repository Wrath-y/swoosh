<?php

namespace Src\Event;

use App\Kernel;
use Src\Support\Core;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Swoole\Server\Task;
use App\Services\ChatRedisService;

class WebSocketEvent
{
    private $app;

    public function __construct(Core $app)
    {
        $this->app = $app;
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
        $this->app->get('redis_pool');
        $this->app->get('db_pool');
    }

    /**
     * Execute when open link
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\Http\Request $request
     */
    public function onOpen(Server $server, Request $request)
    {
        $server->push($request->fd, json_encode(success([
            'fd' => $request->fd
        ])));
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\WebSocket\Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $dispatcher = $this->app->get('dispatcher');
        $dispatcher->wsHandle($server, $frame);
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $server
     * @param $fd
     */
    public function onClose($server, $fd)
    {
    }

    /**
     * @param Server $server
     * @param \Swoole\Server\Task $task
     * @return string
     */
    public function onTask(Server $server, Task $task)
    {
        $task->finish($task->data);
    }

    /** Execute when $server->finish($data) on onTask
     * @param Server $server
     * @param int $task_id
     * @param string $data
     * @return mixed
     */
    public function onFinish(Server $server, int $task_id, $data)
    {
    }
}