<?php

namespace Src\Core\Contexts;

use Swoole\Coroutine;

class RedisContext
{
    /**
     * @var array Coroutine context
     */
    public static $context;

    public static function get()
    {
        return self::getCoroutineContext();
    }

    public static function set($obj)
    {
        $coroutineId = Coroutine::getCid();
        self::$context[$coroutineId] = $obj;
    }

    /**
     * Get data from coroutine context by key
     *
     * @param string $key key of context
     * @return mixed|null
     */
    private static function getCoroutineContext()
    {
        $coroutineId = Coroutine::getCid();
        if (!isset(self::$context[$coroutineId])) {
            return null;
        }

        $coroutineContext = self::$context[$coroutineId];
        if (isset($coroutineContext)) {
            return $coroutineContext();
        }

        return null;
    }

    public static function clearCidContext()
    {
        $coroutineId = Coroutine::getCid();
        if (isset(self::$context[$coroutineId])) {
            unset(self::$context[$coroutineId]);
        }
    }
}
