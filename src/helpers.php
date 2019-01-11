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

function tap($value, $callback)
{
    $callback($value);

    return $value;
}

/**
 * Flatten a multi-dimensional array into a single level.
 *
 * @param  array  $array
 * @param  int  $depth
 * @return array
 */
function flatten($array, $depth = INF) {
    $result = [];

    foreach ($array as $item) {
        if (!is_array($item)) {
            $result[] = $item;
        } elseif ($depth === 1) {
            $result = array_merge($result, array_values($item));
        } else {
            $result = array_merge($result, flatten($item, $depth - 1));
        }
    }

    return $result;
}

function snake($camelCaps, $separator = '_')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camelCaps));
}

function startsWith($haystack, $needles) {
    foreach ((array)$needles as $needle) {
        if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string)$needle) {
            return true;
        }
    }

    return false;
}

function camelize($uncamelized_words, $separator = '_')
{
    $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
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
            return App::get('request')->only($key);
        }

        return App::get('request')->get($key);
    }
}

if (!function_exists('success')) {
    /**
     * Convert success data to JSON
     *
     * @param  array|string  $data
     */
    function success($data)
    {
        $response = [
            'status' => 'success',
            'code' => 0,
        ];
        if (is_string($data)) {
            $response['data'] = $data;
        } else {
            $response['data'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return $response;
    }
}

if (!function_exists('error')) {
    /**
     * Convert error data to JSON
     *
     * @param  array|string  $data
     */
    function error(int $code, $data)
    {
        $response = [
            'status' => 'error',
            'code' => $code,
        ];
        if (is_string($data)) {
            $response['data'] = $data;
        } else {
            $response['data'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return $response;
    }
}

if (!function_exists('redirect')) {
    /**
     * Convert error data to JSON
     *
     * @param  array|string  $data
     */
    function redirect(string $url)
    {
        $response = App::get('response');
        $response->header('Location', env('SERVER_HOST') . ':' . env('SERVER_PORT') . $url);
        $response->status(302);
    }
}
