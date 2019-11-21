<?php

namespace App\Controllers\RPC;

class HealthCheckController
{
    /**
     * @Service('health_check')
     */
    public function healthCheck($data)
    {
        return success([
            "id"=> $data->getId(),
            "name"=> $data->getName(),
        ]);
    }
}