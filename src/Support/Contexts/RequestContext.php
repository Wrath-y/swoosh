<?php

namespace Src\Support\Contexts;

use Src\Server\RequestServer;
use Src\Server\ResponseServer;
use Swoole\Coroutine;

class RequestContext
{
    /**
     * @var array Coroutine context
     */
    public static $context;

    /**
     * Key of current Request
     */
    const REQUEST_KEY = 'request';

    /**
     * Key of current Response
     */
    const RESPONSE_KEY = 'response';

    public static function getRequest()
    {
        return self::getCoroutineContext(self::REQUEST_KEY);
    }

    public static function getResponse()
    {
        return self::getCoroutineContext(self::RESPONSE_KEY);
    }

    public static function setRequest(RequestServer $request)
    {
        $coroutineId = Coroutine::getCid();
        self::$context[$coroutineId][self::REQUEST_KEY] = $request;
    }

    public static function setResponse(ResponseServer $response)
    {
        $coroutineId = Coroutine::getCid();
        self::$context[$coroutineId][self::RESPONSE_KEY] = $response;
    }

    /**
     * Get data from coroutine context by key
     *
     * @param string $key key of context
     * @return mixed|null
     */
    private static function getCoroutineContext(string $key)
    {
        $coroutineId = Coroutine::getCid();
        if (!isset(self::$context[$coroutineId])) {
            return null;
        }

        $coroutineContext = self::$context[$coroutineId];
        if (isset($coroutineContext[$key])) {
            return $coroutineContext[$key];
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
