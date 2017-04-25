<?php
use zkami\Controller;
define('CORE_PATH', __DIR__.'/../zkami/');
define('APP_PATH', __DIR__.'/../application/');
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);

require CORE_PATH . 'Loader.php';
\zkami\Loader::register();