<?php

/*spl_autoload_register(function($class){
	$class = trim($class, '\\');
	if (substr($class, 0, 5) == 'zkami') {
		$filepath = FRAME_PATH.substr($class, 6).'.php';
	} elseif (substr($class, 0, 3) == 'app') {
		$filepath = FRAME_PATH.str_replace('\\','/',substr($class, 4)).'.php';
	} else {
		return;
	}
	if (file_exists($filepath)) {
		require $filepath;
	}
});*/
//加载引导文件
require '../zkami/Core.php';

// 加载基础文件
require '../zkami/zkami.php';