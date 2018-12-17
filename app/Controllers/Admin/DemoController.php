<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;


/**
 * DemoController
 *
 * @Map('/demo')
 * @Mid('auth')
 */
class DemoController extends Controller
{
    /**
     * @Get('/demo')
     */
    public function index()
    {
        print_r('index');
        return 'index';
    }

    /**
     * @Get('/demo/{id}')
     */
    public function show($id)
    {
        return 'show';
    }

    /**
     * @Put('/demo/{id}')
     */
    public function update($id)
    {
        return 'update';
    }

    /**
     * @Post('/demo')
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
