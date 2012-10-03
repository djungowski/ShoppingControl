<?php
require_once '../bootstrap.php';

// Use Zend_Layout
Zend_Layout::startMvc();

// Load Config
$config = new Zend_Config_Ini(BASEPATH . '/config/config.ini');
// Register Config with registry
Zend_Registry::set('config', $config);
// Set timezone according to config
date_default_timezone_set($config->general->timezone);
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

// Check if a user is logged in
$authAdapter = new Zend_Auth_Adapter_DbTable(
    $db,
    'users',
    'username',
    'password'
);
$username = 'blubb';
$password = '';

$authAdapter->setIdentity($username);
$authAdapter->setCredential($password);

$temp = $authAdapter->authenticate();
var_dump($temp->isValid());

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