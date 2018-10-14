<?php

use Src\Tool\Str;

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

    if (strlen($value) > 1 && Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
        return substr($value, 1, -1);
    }

    return $value;
}

if (!function_exists('dd')) {
    /**
     * 调试打印
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
