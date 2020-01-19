<?php

namespace App\Controllers\RPC;

class HealthCheckController
{
    /**
     * @Service('blog')
     */
    public function blog($data)
    {
        return success([
            "id"=> $data->getId(),
            "name"=> $data->getName(),
        ]);
    }

    /**
     * @Get('/health_check')
     */
    public function healthCheck1()
    {
        return success();
    }
}