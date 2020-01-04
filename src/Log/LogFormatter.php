<?php

namespace Src\Log;

class LogFormatter
{
    public static function arrayToStr(array $arr): string
    {
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    public static function format($data): string
    {
        if (is_array($data)) {
            return self::arrayToStr($data);
        }

        return $data;
    }
}