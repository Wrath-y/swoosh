<?php

namespace Src\Event;

use App\Kernel;
use Src\Support\Core;
use Swoole\Websocket\Server;
use App\Services\ChatService;
use Src\Server\RequestServer;
use Swoole\Http\Request;
use Src\Server\ResponseServer;
use Swoole\Http\Response;

class WebSocketEvent
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
        $this->app->get('redis_pool');
        $this->app->get('db_pool');
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
     * Execute when open link
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\Http\Request $request
     */
    public function onOpen(Server $server, Request $request)
    {
        $server->push($request->fd, json_encode([
            'fd' => $request->fd
        ]));
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $server
     * @param Swoole\WebSocket\Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $data = json_decode($frame->data);
        if ($data === 'fetchUserList') {
            foreach ($server->connections as $fd) {
                $server->push($fd, $frame->data);
            }
        } else if (!empty($data->fd) && $server->connection_info($data->fd)) {
            $server->push($data->fd, json_encode([
                'source_fd' => $frame->fd,
                'data' => $data,
            ]));
        } else {
            print_r();
            $server->push($frame->fd, json_encode([
                'data' => 'Target connection has closed',
            ]));
        }
    }

    /**
     * Execute when receive message from client
     *
     * @param Swoole\WebSocket\Server $server
     * @param $fd
     */
    public function onClose($server, $fd)
    {
        $user_list = ChatService::userList();
        foreach ($user_list as $user) {
            if (strpos($user, ":\"$fd\"}")) {
                $user = json_decode($user);
                ChatService::delete($user->name);
                break;
            }
        }
        $data = json_encode('fetchUserList');
        foreach ($server->connections as $f) {
            $server->push($f, $data);
        }
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