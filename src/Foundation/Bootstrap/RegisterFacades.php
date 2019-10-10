<?php

namespace Src\Foundation\Bootstrap;

use Src\Support\App;
use Src\Foundation\AliasLoader;
use Src\Support\Facades\Facade;


class RegisterFacades
{
    public function bootstrap(App $app)
    {
        Facade::setFacadeApplication($app);

        AliasLoader::getInstance(
            $app->get('config')->get('app.aliases')
        )->register();
    }
}
