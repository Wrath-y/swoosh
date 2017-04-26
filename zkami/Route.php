<?php
namespace zkami;
/**
* 
*/
class Route
{
    protected $controller = 'index';
    protected $action = 'index';
    protected $param = [];
    public static function start()
    {
        $this->getUrl();
    }
    public function getUrl()
    {
        $url = empty($_GET['url']) ? false : $_GET['url'];
        if (!empty($url)) {
            $urlArray = explode('/', $url);
            $urlArray = array_filter($urlArray);
            //Get the controller
            $controller = ucfirst($urlArray[0]);
            //Get the action
            $action = ucfirst($urlArray[1]);
            //Get the param
            $param = 
        }
    }
}