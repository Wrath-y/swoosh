<?php

namespace Src\Support;

use Src\App;

class Config
{
    private $config;

    public function __construct()
    {
        $path = App::getPath('/config/app.php');
        $this->config = include $path;
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