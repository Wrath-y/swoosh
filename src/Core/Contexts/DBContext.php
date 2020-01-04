<?php

namespace Src\Core\Contexts;

use Closure;
use Swoole\Coroutine;

class DBContext
{
    /**
     * @param string $key key of context
     */
    public static $context;

    public static function get($key)
    {
        return self::getCoroutineContext($key);
    }

    /**
     * set data into coroutine context by key
     *
     * @param string $key key of context
     */
    public static function set($key, $obj)
    {
        $coroutineId = Coroutine::getCid();
        self::$context[$coroutineId][$key] = $obj;
    }

    /**
     * Get data from coroutine context by key
     *
     * @param string $key key of context
     * @return mixed|null
     */
    private static function getCoroutineContext($key)
    {
        $coroutineId = Coroutine::getCid();
        if (!isset(self::$context[$coroutineId][$key])) {
            return null;
        }

        $coroutineContext = self::$context[$coroutineId][$key];
        self::clearCidContext();
        if ($coroutineContext instanceof Closure) {
            return $coroutineContext();
        } else {
            return $coroutineContext;
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
