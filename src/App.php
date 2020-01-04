<?php

namespace Src;

use Dotenv\Dotenv;
use Src\Core\Config;

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
        self::$app = new \Src\Core\App();
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

    /**
     * Initialization service
     */
    public function destructServices()
    {
        foreach (self::$app->get('config')->get('destruct') as $className){
            (new $className(self::$app))->destruct();
        }
    }

    public function command(array $args)
    {
        if (count($args) == 1) {
            $is_close = self::$app->get('http')->start();   
        } else {
            foreach ($args as $value) {
                switch ($value) {
                    case 'rpc':
                        $is_close = self::$app->get('rpc_server')->start();
                        break;
                    case 'ws':
                        $is_close = self::$app->get('ws')->start();
                        break;
                    case 'http':
                        $is_close = self::$app->get('http')->start();
                        break;
                }
            }
        }
        

        if ($is_close) {
            $this->destructServices();
        }
    }
}