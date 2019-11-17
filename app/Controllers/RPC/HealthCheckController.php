<?php

namespace App\Controllers\RPC;

use App\Controllers\Controller;

class HealthCheckController extends Controller
{
    /**
     * @Service('/health_check')
     */
    public function get()
    {
        return success('');
    }
}