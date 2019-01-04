<?php

namespace Src\Provider;

use Src\App;
use Src\Server\DispatcherServer;

class DispatchServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('dispatcher', function () {
            return new DispatcherServer($this->app);
        });
    }
}