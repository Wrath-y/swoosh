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
