<?php

namespace Src\Server;

use Swoole\Http\Response;
use Src\Server\Database\Eloquent\Model;

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
        if (is_array($html) && current($html) instanceof Model) {
            foreach ($html as &$value) {
                $value = $value->getAttributes();
            }
        } else if (is_object($html && $html instanceof Model)) {
            $html = $html->getAttributes();
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