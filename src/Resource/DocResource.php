<?php

namespace Src\Resource;

use Src\App;

class DocResource
{
    private $routeTableServer;

    public function __construct()
    {
        $this->routeTableServer = App::getSupport('routeTableServer');
    }
    public function setRestful(string $url, string $class)
    {
        $this->setIndex($url, $class);
        $this->setShow($url, $class);
        $this->setPost($url, $class);
        $this->setUpdate($url, $class);
        $this->setDelete($url, $class);
    }

    public function setByType(string $type, string $url, string $class)
    {
        switch ($type) {
            case 'Get':
                $this->setIndex($url, $class);
                break;
            case 'Post':
                $this->setIndex($url, $class);
                break;
            case 'Update':
                $this->setIndex($url, $class);
                break;
            case 'Delete':
                $this->setIndex($url, $class);
                break;
            default:
                break;
        }
    }

    public function setIndex(string $url, string $class)
    {
        $this->routeTableServer->set('get@' . $url, [
            'type' => 'get',
            'controller' => $class,
            'method' => 'index',
        ]);
    }

    public function setShow(string $url, string $class)
    {
        $this->routeTableServer->set('get@' . $url . '{/id}', [
            'type' => 'get',
            'controller' => $class,
            'method' => 'show',
        ]);
    }

    public function setPost(string $url, string $class)
    {
        $this->routeTableServer->set('post@' . $url, [
            'type' => 'post',
            'controller' => $class,
            'method' => 'store',
        ]);
    }

    public function setUpdate(string $url, string $class)
    {
        $this->routeTableServer->set('put@' . $url . '{id}', [
            'type' => 'put',
            'controller' => $class,
            'method' => 'update',
        ]);
    }

    public function setDelete(string $url, string $class)
    {
        $this->routeTableServer->set('delete@' . $url . '{id}', [
            'type' => 'delete',
            'controller' => $class,
            'method' => 'destroy',
        ]);
    }
}
