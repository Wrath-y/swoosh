<?php

namespace Src;

use Dotenv\Dotenv;
use Src\Provider\AbstractProvider;
use Src\Support\Config;
use Src\Support\Core;

class App
{
    private static $path;
    protected static $app;

    public function __construct($root)
    {
        self::$path = $root;
        // Prioritize loading environment variables
        if (file_exists(self::getPath('/.env'))){
            (new Dotenv($root))->load();
        }

        // Load config
        self::$app = new Core();
        self::$app->set('config', function (){
            return new Config();
        });
    }

    /**
     * Get the path of the relative project
     *
     * @param string $path
     * @return string
     */
    public static function getPath(string $path='')
    {
        return self::$path.$path;
    }

    public static function get(string $name)
    {
        return self::$app->get($name);
    }

    /**
     * Initialization service
     *
     * @param array $bootstraps
     */
    public function initializeServices(array $bootstraps)
    {
        foreach ($bootstraps as $className){
            (new $className(self::$app))->register();
        }
    }

    public function start($type = '')
    {
        switch ($type) {
            case 'ws':
                self::$app->get('ws')->start();
                break;
            case 'http':
            default:
                self::$app->get('http')->start();
                break;
        }
    }
}