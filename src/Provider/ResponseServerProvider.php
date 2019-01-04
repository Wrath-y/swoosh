<?php

namespace Src\Provider;

use Src\App;

use Src\Server\ResponseServer;

class ResponseServerProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('response',function (){
            return new ResponseServer();
        });
    }
}