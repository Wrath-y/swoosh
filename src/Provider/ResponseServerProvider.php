<?php

namespace Src\Provider;

use Src\App;

use Src\Server\ResponseServer;

class ResponseServerProvider extends AbstractProvider
{
    protected $serviceName = 'response';

    public function register()
    {
        $this->app->set($this->serviceName,function (){
            return new ResponseServer();
        });
    }
}