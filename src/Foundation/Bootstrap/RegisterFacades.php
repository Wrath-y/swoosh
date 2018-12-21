<?php

namespace Src\Foundation\Bootstrap;

use Src\Support\Core;
use Src\Foundation\AliasLoader;
use Src\Support\Facades\Facade;


class RegisterFacades
{
    public function bootstrap(Core $app)
    {
        Facade::setFacadeApplication($app);

        AliasLoader::getInstance(
            $app->get('config')->get('aliases')
        )->register();
    }
}
