<?php

namespace Src\Provider;

use Src\App;

class DatabaseProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('db.factory', function () {
            return new ConnectionFactory($this->app);
        });
        $this->app->set('db', function () {
            return new DatabaseManager($this->app);

        });
        $this->app->set('db.connection', function () {
            return $this->app['db']->connection();
        });
    }
}
