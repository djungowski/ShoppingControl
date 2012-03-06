<?php
require_once '../bootstrap.php';
require_once LIBPATH . '/Zend/Loader/Autoloader.php';

// Register the autoloader
$loader = Zend_Loader_Autoloader::getInstance();

// Use Zend_Layout
Zend_Layout::startMvc();

// Load Config
$config = new Zend_Config_Ini(BASEPATH . '/config/config.ini');
// Register Config with registry
Zend_Registry::set('config', $config);
// Load Language files according to config
$translations = new Zend_Config_Ini(BASEPATH . '/language/' . $config->general->language .'.ini', 'translations');
// Register translations with registry
Zend_Registry::set('translations', $translations);
// Create database connection with SQLite DB
$db = Zend_Db::factory(
    'pdo_sqlite',
    array(
        'dbname' => BASEPATH . '/db/sc.db'
    )
);
// Register db with registry
Zend_Registry::set('db', $db);

$front = Zend_Controller_Front::getInstance();
$front->setParam('noErrorHandler', true);
$front->throwExceptions(true);

$router = new Zend_Controller_Router_Rewrite();
$router->addRoute(
    'default',
    new Zend_Controller_Router_Route(
        ':controller/:action/*',
        array(
            'module' => 'default',
            'controller' => 'default',
            'action' => 'index'
        )
    )
);
$front->setRouter($router);
$front->setControllerDirectory(BASEPATH . '/controllers');

$front->dispatch();