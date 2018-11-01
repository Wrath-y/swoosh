<?php

namespace App\Controllers\Admin;

/**
 * DemoController
 *
 * @Mapp('demo')
 */
class DemoController
{
    /**
     * this test
     * @Get('demo/{id}')
     */
    public function Demo()
    {
        return 'hello';
    }
}
