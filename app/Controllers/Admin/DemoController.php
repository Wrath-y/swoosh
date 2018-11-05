<?php

namespace App\Controllers\Admin;

/**
 * DemoController
 *
 * @Mapp('/demo')
 */
class DemoController
{
    /**
     * @Get('/demo')
     */
    public function index()
    {
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
