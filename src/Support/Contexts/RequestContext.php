<?php

namespace Src\Support\Contexts;

use Src\Server\RequestServer;
use Src\Server\ResponseServer;
use Swoole\Coroutine;

class RequestContext
{
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

    /**
     * @var array Coroutine context
     */
    private static $context;

    public static function setRequest(RequestServer $request)
    {
        $coroutineId = Coroutine::getuid();
        self::$context[$coroutineId][self::REQUEST_KEY] = $request;
    }

    public static function setResponse(ResponseServer $response)
    {
        $coroutineId = Coroutine::getuid();
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
        $coroutineId = Coroutine::getuid();
        if (!isset(self::$context[$coroutineId])) {
            return null;
        }

        $coroutineContext = self::$context[$coroutineId];
        if (isset($coroutineContext[$key])) {
            return $coroutineContext[$key];
        }
        return null;
    }
}
