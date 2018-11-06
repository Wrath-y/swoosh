<?php

use Src\App;
use Src\Helper\StringHelper;

/**
 * @param      $key
 * @param null $default
 * @return array|bool|false|null|string
 */
function env($key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }

    if (strlen($value) > 1 && StringHelper::startsWith($value, '"') && StringHelper::endsWith($value, '"')) {
        return substr($value, 1, -1);
    }

    return $value;
}

if (!function_exists('dd')) {
    /**
     * print
     *
     * @param $var
     */
    function dd($var)
    {
        foreach (func_get_args() as $var) {
            \Symfony\Component\VarDumper\VarDumper::dump($var);
        }
        exit(0);
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     */
    function request($key = null)
    {
        if (is_array($key)) {
            return App::getSupport('request')->only($key);
        }

        return App::getSupport('request')->get($key);
    }
}

if (!function_exists('response')) {
    /**
     * Convert response data to JSON
     *
     * @param  array|string  $data
     */
    function response($data)
    {
        if (is_string($data)) {
            return $data;
        }
    }
}
