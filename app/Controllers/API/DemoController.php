<?php

namespace App\Controllers\API;

use Protos\User;
use App\Models\ChatLog;
use App\Controllers\Controller;
use App\Events\RegisterEvent;
use App\Models\User as ModelsUser;
use App\Services\ChatRedisService;
use Src\App;
use Src\RPCClient\RPCClient;

class DemoController extends Controller
{
    /**
     * @Get('/api/demo')
     */
    public function index()
    {
        // App::get('rpc_stub')->destruct();
        $user = new User();
        $user->setId(1);
        $user->setName("ysama");
        $client = new RPCClient;
        $client->send('blog', $user);
        $res = $client->recv();
        $client->close();
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

        // event(new RegisterEvent(new ModelsUser));
        return success();
    }
}
