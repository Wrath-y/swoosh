<?php

namespace Src\Event\Provider;

use Src\Event\EventServer;
use Src\Core\AbstractProvider;

class EventServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('event', function () {
            return new EventServer($this->app);
        });
    }
}