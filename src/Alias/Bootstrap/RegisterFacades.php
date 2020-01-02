<?php

namespace Src\Alias\Bootstrap;

use Src\Support\App;
use Src\Alias\AliasLoader;
use Src\Alias\Facades\Facade;


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
