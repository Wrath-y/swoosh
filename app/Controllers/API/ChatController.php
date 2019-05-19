<?php

namespace App\Controllers\API;

use App\Models\ChatLog;
use App\Services\ChatRedisService;
use App\Controllers\Controller;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * @Get('/api/users')
     */
    public function index()
    {
        return success(ChatRedisService::userList());
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
