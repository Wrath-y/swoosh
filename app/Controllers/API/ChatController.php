<?php

namespace App\Controllers\API;

use App\Controllers\Controller;
use App\Services\ChatService;

class ChatController extends Controller
{
    /**
     * @Get('/api/users')
     */
    public function index()
    {
        return '123';
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
