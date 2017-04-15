<?php

echo "zkami.php";
defined('FRAME_PATH') or define('FRAME_PATH', __DIR__.'/');
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
defined('APP_DEBUG') or define('APP_DEBUG', false);
defined('CONFIG_PATH') or define('CONFIG_PATH', APP_PATH.'config/');
defined('RUNTIME_PATH') or define('RUNTIME_PATH', APP_PATH.'runtime/');

//类文件拓展名
const EXT = '.class.php';
//包含配置文件
require APP_PATH . 'config/config.php';
//包含核心框架类
require FRAME_PATH . 'Core.php';
//实例核心类
$core = new Core();
$core->run();