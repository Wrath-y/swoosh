<?php

namespace Src\Provider;

use Src\App;
use Src\Server\Database\Eloquent\Model;
use Src\Server\Database\DatabaseManager;
use Src\Server\Database\Connectors\ConnectionFactory;

class DatabaseProvider extends AbstractProvider
{
    public function register()
    {
        $this->app->set('db.factory', function () {
            return new ConnectionFactory();
        });
        $this->app->set('db', function () {
            $config = $this->app->get('config')->get('database.connections');

            return new DatabaseManager($config);

        });
        $this->app->set('db.connection', function () {
            return $this->app->get('db')->connection();
        });

        Model::setConnectionResolver($this->app->get('db'));
    }
}
