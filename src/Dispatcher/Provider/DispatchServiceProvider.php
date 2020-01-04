<?php

namespace Src\Dispatcher\Provider;

use Src\Core\AbstractProvider;
use Src\Dispatcher\DispatcherServer;

class DispatchServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('dispatcher', function () {
            return new DispatcherServer($this->app);
        });
    }
}