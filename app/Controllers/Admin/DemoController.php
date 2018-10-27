<?php

namespace App\Controllers\Admin;

/**
 * DemoController
 *
 * @Map('demo')
 */
class DemoController
{
    /**
     * this test
     * @Get("demo")
     */
    public function Demo()
    {
        return 'hello';
    }
}
