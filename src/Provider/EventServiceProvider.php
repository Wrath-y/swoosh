<?php

namespace Src\Provider;

use Src\Server\Event\EventServer;

class EventServiceProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('event', function () {
            return new EventServer($this->app);
        });
    }
}