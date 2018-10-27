<?php

namespace Src;

use Dotenv\Dotenv;
use Src\Provider\AbstractProvider;
use Src\Support\Config;
use Src\Support\Core;

class App
{
    private static $path;
    protected static $support;

    public function __construct($root)
    {
        self::$path = $root;
        // prioritize loading environment variables
        if (file_exists(self::getPath('/.env'))){
            (new Dotenv($root))->load();
        }

        // load config
        self::$support = new Core();
        self::$support->set('config',function (){
            return new Config();
        });
    }

    /**
     * Get the path of the relative project
     *
     * @param $path
     * @return string
     */
    public static function getPath($path='')
    {
        return self::$path.$path;
    }

    public static function getSupport(string $name)
    {
        return self::$support->get($name);
    }

    /**
     * initialization service
     *
     * @param $arrConfig
     */
    public function initializeServices($arrConfig)
    {
        foreach ($arrConfig as $className){
            (new $className(self::$support))->register();
        }
    }

    public function start()
    {
        self::$support->get('http')->start();
    }
}