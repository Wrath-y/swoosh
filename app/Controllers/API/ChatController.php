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
        return success(ChatService::userList());
    }

    /**
     * @Put('/api/users')
     */
    public function update()
    {
        return ChatService::update(request('data'));
    }

    /**
     * @Post('/api/users')
     */
    public function store()
    {
        dd(request());
        return 'store';
    }

    /**
     * @Delete('/demo/{id}')
     */
    public function destroy($id)
    {
        return 'destroy';
    }
}
