<?php

namespace App\Controllers\API;

use Protos\User;
use App\Models\ChatLog;
use App\Controllers\Controller;
use App\Services\ChatRedisService;
use Src\Server\RPCClient\RPCClient;

class ChatController extends Controller
{
    /**
     * @Get('/api/users')
     */
    public function index()
    {
        $user = new User();
        $user->setId(1);
        $user->setName("ysama");
        $connection = (new RPCClient)->makeConnection();
        $connection->send('health_check', $user);
        $res = $connection->recv();
        $connection->close();
        return success($res);
        // $res = new User();
        // $res->mergeFromString($packed);
        // $jsonArr = [
        //     "id"=> $res->getId(),
        //     "name"=> $res->getName(),
        // ];
        // return success($jsonArr);
        // return success(ChatRedisService::userList());
        // return success(User::get());
    }

    /**
     * @Post('/api/users')
     */
    public function store()
    {
        return success(ChatRedisService::set(request('data')));
    }

    /**
     * @Delete('/api/users')
     */
    public function destroy()
    {
        return success(ChatRedisService::delete(request('name')));
    }

    /**
     * @Get('/api/chat-logs')
     */
    public function chatLogs()
    {
        $data = ChatLog::where('target_name', request('target_name'))->where('source_name', request('source_name'))->get();

        return success($data);
    }
}
