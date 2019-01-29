<?php

namespace App\Controllers\API;

use App\Models\Order;
use App\Services\ChatService;
use App\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * @Get('/api/users')
     */
    public function index()
    {
        return Order::where('id', 1)->get();
    }

    /**
     * @Post('/api/users')
     */
    public function store()
    {
        return success(ChatService::set(request('data')));
    }

    /**
     * @Delete('/api/users')
     */
    public function destroy()
    {
        return success(ChatService::delete(request('name')));
    }
}
