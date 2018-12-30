<?php

namespace Src\Server;

use Swoole\Http\Response;

class ResponseServer extends Response
{
    protected $response;

    public function set(Response $response)
    {
        $this->response = $response;
    }

    public function end($html = null)
    {
        if ( is_string($html) ) {
            $this->header('Content-Type', 'text/html; charset=UTF-8');
        } else {
            $this->header('Content-Type', 'application/json; charset=UTF-8');
            $html = json_encode($html, JSON_UNESCAPED_UNICODE);
        }

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