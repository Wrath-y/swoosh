<?php

namespace Src\Support;

use Src\App;
use Src\Resource\AnnotationResource;

class Config
{
    private $config;

    public function __construct()
    {
        $path = App::getPath('/config');
        $configFiles = (new AnnotationResource)->scanPhpFile($path);
        foreach ($configFiles as $file) {
            $file = explode('\\', $file)[1];
            $this->config[$file] = include $path . "/$file.php";
        }
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function get($key, $default = null)
    {
        if ($key === null) {
            return $this->config;
        } elseif (!strpos($key, '.')) {
            return isset($this->config[$key]) ? $this->config[$key] : $default;
        }
        $arrConfigKey = explode('.', $key);
        if (!isset($this->config[$arrConfigKey[0]])) {
            return $default;
        }
        $config = $this->config[$arrConfigKey[0]];
        unset($arrConfigKey[0]);
        foreach ($arrConfigKey as $num => $keyNext) {
            if (!isset($config[$keyNext])) {
                return $default;
            } else {
                $config = $config[$keyNext];
            }
        }

        return $config;
    }
}
