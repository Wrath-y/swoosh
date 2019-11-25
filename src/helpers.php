<?php

use Src\App;
use Src\Helper\StringHelper;
use Src\Support\Contexts\RequestContext;

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
function flatten($array, $depth = INF)
{
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

/**
 * @param  array  $array
 * @param  array|string  $keys
 * @return array
 */
function array_except(array $array, $keys)
{
    $keys = (array) $keys;
    foreach ($keys as $value) {
        unset($array[$value]);
    }

    return $array;
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

function class_basename($class)
{
    $class = is_object($class) ? get_class($class) : $class;

    return basename(str_replace('\\', '/', $class));
}

function pluralize($string)
{
    $plural = [
        ['/(quiz)$/i', "$1zes"],
        ['/^(ox)$/i', "$1en"],
        ['/([m|l])ouse$/i', "$1ice"],
        ['/(matr|vert|ind)ix|ex$/i', "$1ices"],
        ['/(x|ch|ss|sh)$/i', "$1es"],
        ['/([^aeiouy]|qu)y$/i', "$1ies"],
        ['/([^aeiouy]|qu)ies$/i', "$1y"],
        ['/(hive)$/i', "$1s"],
        ['/(?:([^f])fe|([lr])f)$/i', "$1$2ves"],
        ['/sis$/i', "ses"],
        ['/([ti])um$/i', "$1a"],
        ['/(buffal|tomat)o$/i', "$1oes"],
        ['/(bu)s$/i', "$1ses"],
        ['/(alias|status)$/i', "$1es"],
        ['/(octop|vir)us$/i', "$1i"],
        ['/(ax|test)is$/i', "$1es"],
        ['/s$/i', "s"],
        ['/$/', "s"],
        ["/s$/", ""],
        ["/(n)ews$/", "$1ews"],
        ["/([ti])a$/", "$1um"],
        ["/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/", "$1$2sis"],
        ["/(^analy)ses$/", "$1sis"],
        ["/([^f])ves$/", "$1fe"],
        ["/(hive)s$/", "$1"],
        ["/(tive)s$/", "$1"],
        ["/([lr])ves$/", "$1f"],
        ["/([^aeiouy]|qu)ies$/", "$1y"],
        ["/(s)eries$/", "$1eries"],
        ["/(m)ovies$/", "$1ovie"],
        ["/(x|ch|ss|sh)es$/", "$1"],
        ["/([m|l])ice$/", "$1ouse"],
        ["/(bus)es$/", "$1"],
        ["/(o)es$/", "$1"],
        ["/(shoe)s$/", "$1"],
        ["/(cris|ax|test)es$/", "$1is"],
        ["/([octop|vir])i$/", "$1us"],
        ["/(alias|status)es$/", "$1"],
        ["/^(ox)en/", "$1"],
        ["/(vert|ind)ices$/", "$1ex"],
        ["/(matr)ices$/", "$1ix"],
        ["/(quiz)zes$/", "$1"],
    ];

    $irregular = [
        ['move', 'moves'],
        ['sex', 'sexes'],
        ['child', 'children'],
        ['man', 'men'],
        ['person', 'people']
    ];

    $uncountable = [
        'sheep',
        'fish',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment'
    ];

    if (in_array(strtolower($string), $uncountable)) return $string;

    foreach ($irregular as $noun) {
        if (strtolower($string) == $noun[0])
            return $noun[1];
    }

    foreach ($plural as $pattern) {
        if (preg_match($pattern[0], $string))
            return preg_replace($pattern[0], $pattern[1], $string);
    }
    return $string;
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
            return RequestContext::getRequest()->only($key);
        }
        if (is_string($key)) {
            return RequestContext::getRequest()->get($key);
        }

        return RequestContext::getRequest();
    }
}

if (!function_exists('success')) {
    /**
     * Convert success data to JSON
     *
     * @param  array|string  $data
     */
    function success($data = '', bool $toJson = false)
    {
        if ($toJson) {
            return json_encode([
                'status' => 'success',
                'code' => 0,
                'data' => $data
            ]);
        }
        return [
            'status' => 'success',
            'code' => 0,
            'data' => $data
        ];
    }
}

if (!function_exists('error')) {
    /**
     * Convert error data to JSON
     *
     * @param  array|string  $data
     */
    function error(int $code, $data, bool $toJson = false)
    {
        return [
            'status' => 'error',
            'code' => $code,
            'data' => $data
        ];
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

if (!function_exists('str_to_bin')) {
    /**
     * hexdec 16 - 10
     * decbin 10 - 2
     * bindec 2 - 10
     * dechex 10 - 16
     */
    function str_to_bin($str){
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        foreach($arr as &$v){
            $hex = unpack('h*', $v);
            $v = decbin(hexdec($hex[1]));
            unset($hex);
        }
        
        return join(' ',$arr);
    }
}

if (!function_exists('bin_to_str')) {
    function bin_to_str($str)
    {
        $arr = explode(' ', $str);
        foreach ($arr as &$v) {
            $v = pack("h*", dechex(bindec($v)));
        }

        return join('', $arr);
    }
}