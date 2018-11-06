<?php

namespace Src\Server;

use Src\App;
use Swoole\Http\Request;

class RequestServer
{
    protected $request = [];

    public function get($key = null)
    {
        if (is_null($key)) {
            return $this->request;
        }
        $arr = array_merge($this->request->post, $this->request->get);
        if (isset($arr[$key])) {
            return $arr[$key];
        }
    }

    public function only(array $key)
    {
        $res = [];
        $arr = array_merge($this->request->post, $this->request->get);
        foreach ($key as $k) {
            if (isset($arr[$k])) {
                $res = $arr[$k];
            }
        }

        return $res;
    }

    public function set(Request $request)
    {
        $this->request = $request;
    }
}
