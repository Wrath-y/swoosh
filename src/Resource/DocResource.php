<?php

namespace Src\Resource;

use Src\App;

class DocResource
{
    private $routeTable;
    private $middleware;

    public function __construct()
    {
        $this->routeTable = App::get('routeTable');
    }

    public function setMiddleware(string $middleware)
    {
        $this->middleware = $middleware;
    }

    public function setRestful(string $url, string $class)
    {
        $this->setGet($url, $class);
        $url = $url . '/{}';
        $this->setGet($url, $class);
        $this->setPost($url, $class);
        $this->setPut($url, $class);
        $this->setDelete($url, $class);
    }

    public function setByType(string $type, string $con, string $class, string $method)
    {
        switch ($type) {
            case 'Get':
                $this->setGet($con, $class, $method);
                break;
            case 'Post':
                $this->setPost($con, $class, $method);
                break;
            case 'Put':
                $this->setPut($con, $class, $method);
                break;
            case 'Delete':
                $this->setDelete($con, $class, $method);
                break;
            default:
                break;
        }

        // Revert middleware
        $this->setMiddleware('');
    }

    public function setGet(string $url, string $class, string $method = '')
    {
        if (!$method && preg_match('/\{/i', $url)) {
            $method = 'show';
        }
        $method = $method ?? 'index';

        $this->routeTable->set('get@' . $url, [
            'type' => 'get',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }

    public function setPost(string $url, string $class, string $method = '')
    {
        $method = $method ?? 'store';
        $this->routeTable->set('post@' . $url, [
            'type' => 'post',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }

    public function setPut(string $url, string $class, string $method = '')
    {
        $method = $method ?? 'update';
        $this->routeTable->set('put@' . $url, [
            'type' => 'put',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }

    public function setDelete(string $url, string $class, string $method = '')
    {
        $method = $method ?? 'destroy';
        $this->routeTable->set('delete@' . $url, [
            'type' => 'delete',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }
}
