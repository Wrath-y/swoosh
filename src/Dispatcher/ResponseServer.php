<?php

namespace Src\Dispatcher;

use Swoole\Http\Response;
use Src\Database\Eloquent\Model;

class ResponseServer
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function end($html = null)
    {
        $this->header('Content-Type', 'application/json; charset=UTF-8');

        if (is_array($html['data']) && current($html['data']) instanceof Model) {
            foreach ($html['data'] as &$value) {
                $value = $value->getAttributes();
            }
        } else if (is_object($html['data'] && $html['data'] instanceof Model)) {
            $html['data'] = $html['data']->getAttributes();
        }
        $html = json_encode($html, JSON_UNESCAPED_UNICODE);

        $this->response->end($html);
    }

    public function header($key, $value, $ucwords = null)
    {
        $this->response->header($key, $value, $ucwords);
    }

    public function cookie($name, $value = null, $expires = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $this->response->cookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }


    /**
     * @param $code 404, 200
     * @param $reason
     */
    public function status($http_code, $reason = NULL)
    {
        $this->response->status($http_code, $reason);
    }
}