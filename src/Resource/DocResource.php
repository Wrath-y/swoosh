<?php

namespace Src\Resource;

use Src\App;

class DocResource
{
    private $routeTable;

    public function __construct()
    {
        $this->routeTable = App::getSupport('routeTable');
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

    public function setByType(string $type, string $url, string $class)
    {
        switch ($type) {
            case 'Get':
                $this->setGet($url, $class);
                break;
            case 'Post':
                $this->setPost($url, $class);
                break;
            case 'Update':
                $this->setPut($url, $class);
                break;
            case 'Delete':
                $this->setDelete($url, $class);
                break;
            default:
                break;
        }
    }

    public function setGet(string $url, string $class)
    {
        $method = 'index';
        if (preg_match('/\{/i', $url)) {
            $method = 'show';
        }
        $this->routeTable->set('get@' . $url, [
            'type' => 'get',
            'controller' => '\\' . $class,
            'method' => $method,
        ]);
    }

    public function setPost(string $url, string $class)
    {
        $this->routeTable->set('post@' . $url, [
            'type' => 'post',
            'controller' => '\\' . $class,
            'method' => 'store',
        ]);
    }

    public function setPut(string $url, string $class)
    {
        $this->routeTable->set('put@' . $url, [
            'type' => 'put',
            'controller' => '\\' . $class,
            'method' => 'update',
        ]);
    }

    public function setDelete(string $url, string $class)
    {
        $this->routeTable->set('delete@' . $url, [
            'type' => 'delete',
            'controller' => '\\' . $class,
            'method' => 'destroy',
        ]);
    }
}
