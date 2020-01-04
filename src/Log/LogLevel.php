<?php

namespace Src\Log;

class LogLevel
{
    public static $LEVELS = [
        'debug' => 0x1,
        'info' => 0x2,
        'warn' => 0x4,
        'err' => 0x8,
        'max' => 0x10
    ];
}