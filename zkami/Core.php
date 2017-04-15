<?php

echo "core.php";
/**
* 
*/
class Core
{
	public function run()
	{
		spl_autoload_register(array($this, 'loadClass'));
		$this->setReporting();
		$this->removeMagicQuotes();
		$this->unregisterGlobals();
		$this->route();
	}

	//路由处理
	public function route()
	{
		$controllerN = 'Index';
		$action = 'index';
		$param = array();

		$url = isset($_GET['url']) ? $_GET['url'] : false;
		if ($url) {
			//分割url
			$urlArray = explode('/', $url);
			//过滤空数组
			$urlArray = array_filter($urlArray);
			//获取控制器名
			$controllerN = ucfirst($urlArray[0]);
			//获取方法名  array_shift()删除数组第一个元素
			array_shift($urlArray);
			$action = $urlArray ? $urlArray[0] : 'index';
			//获取url参数
			array_shift($urlArray);
			$param = $urlArray ? $urlArray : array();
		}

		//实例化控制器
		$controller = $controllerN . 'Controller';
		$dispatch = new $controller($controllerN,$action);

		//如果 $action 所指的方法在 $controller 所指的对象类中已定义，则返回 TRUE，否则返回 FALSE。
		if ((int)method_exists($controller, $action)) {
			//把$param作为array($dispatch, $action)的参数传入
			call_user_func_array(array($dispatch, $action), $param);
		} else {
			die($controller . '控制器不存在');
		}
	}

	//检测开发环境
	public function setReporting() 
	{
		if (APP_DEBUG === true) {
			error_reporting(E_ALL);
			ini_set('display_errors', 'On');
		} else {
			error_reporting(E_ALL);
			ini_set('display_errors', 'Off');
			ini_set('log_errors', 'On');
			ini_set('error_log', RUNTIME_PATH . 'logs/error.log');
		}
	}

	//删除敏感字符
	public function stripSlashesDeep($value)
	{
		$value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripcslashes($value);
		return $value;
	}
	//检测敏感字符并删除
	public function removeMagicQuotes()
	{
		if (get_magic_quotes_gpc()) {
			$_GET = isset($_GET) ? $this->stripSlashesDeep($_GET) : ' ';
			$_POST = isset($_POST) ? $this->stripSlashesDeep($_POST) : ' ';
			$_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : ' ';
			$_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : ' ';
		}
	}

	//检测自定义全局变量(register globals)并移除
	public function unregisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
           foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    //自动加载类控制器和模型类
    public static function loadClass($class)
    {
    	$framework = FRAME_PATH . $class . '.php';
    	$controller = APP_PATH . 'application/controller/' . $class . '.php';
    	$model = APP_PATH . 'application/model/' . $class . '.php';

    	if (file_exists($framework)) {
    		//加载框架核心类
    		require $framework;
    	}
    	if (file_exists($controller)) {
    		//加载应用控制器类
    		require $controller;
    	}
    	if (file_exists($model)) {
    		//加载应用模型类
    		require $model;
    	} else {

    	}
    }
}