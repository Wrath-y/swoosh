<?php

namespace Src\Helper;

use Src\App;

class DocResourceHelper
{
    private $route_table;
    private $middleware;

    public function __construct()
    {
        $this->route_table = App::get('route_table');
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

    public function setByType(string $type, string $name, string $class, string $method)
    {
        switch ($type) {
            case 'Get':
                $this->setGet($name, $class, $method);
                break;
            case 'Post':
                $this->setPost($name, $class, $method);
                break;
            case 'Put':
                $this->setPut($name, $class, $method);
                break;
            case 'Delete':
                $this->setDelete($name, $class, $method);
                break;
            case 'Service':
                $this->setService($name, $class, $method);
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

        $this->route_table->set('get@' . $url, [
            'type' => 'get',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }

    public function setPost(string $url, string $class, string $method = '')
    {
        $method = $method ?? 'store';
        $this->route_table->set('post@' . $url, [
            'type' => 'post',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }

    public function setPut(string $url, string $class, string $method = '')
    {
        $method = $method ?? 'update';
        $this->route_table->set('put@' . $url, [
            'type' => 'put',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }

    public function setDelete(string $url, string $class, string $method = '')
    {
        $method = $method ?? 'destroy';
        $this->route_table->set('delete@' . $url, [
            'type' => 'delete',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }

    public function setService(string $name, string $class, string $method = '')
    {
        $this->route_table->set('service@' . $name, [
            'type' => 'service',
            'controller' => '\\' . $class,
            'method' => $method,
            'middleware' => $this->middleware,
        ]);
    }
}
