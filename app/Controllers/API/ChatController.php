<?php

namespace App\Controllers\API;

use App\Models\Order;
use App\Services\ChatRedisService;
use App\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * @Get('/api/users')
     */
    public function index()
    {
        return success(ChatRedisService::userList());
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
}
