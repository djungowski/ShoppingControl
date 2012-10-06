<?php
define('SHOPPINGCONTROL_VERSION', '0.6.1');

define('BASEPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('LIBPATH', BASEPATH . DIRECTORY_SEPARATOR . 'library');
define('ZENDPATH', LIBPATH . DIRECTORY_SEPARATOR . 'Zend');

set_include_path(get_include_path() . ':' . LIBPATH);

$self = $_SERVER['SCRIPT_NAME'];
if (!empty($self)) {
		$urlPath = dirname ($self);
} else {
		$urlPath = BASEPATH;
}
if ($urlPath == '/') {
    $urlPath = '';
}
define('URLPATH', $urlPath);

// Register the autoloader
require_once LIBPATH . '/Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('ShoppingControl');