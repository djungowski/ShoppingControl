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
// Start session
Zend_Session::start(
    array(
    	'remember_me_seconds' => $config->session->valid
    )
);
$session = new Zend_Session_Namespace($config->session->namespace);
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
// Defaults
$username = 'NyanCat';
$password = new ShoppingControl_Password('q5ryQvNoKE');
// Check if user data is provided with POST or in a Cookie
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = new ShoppingControl_Password($_POST['password']);
    $password->setSalt($config->security->password->salt, $config->security->password->saltlength, $config->security->password->saltstartsat);
} elseif (isset($session->username) && isset($session->password)) {
    $username = $session->username;
    // Salting not needed, password is stored encrypted in cookie
    $password = $session->password;
}

// Check if a user is logged in
$authAdapter = new Zend_Auth_Adapter_DbTable(
    $db,
    'users',
    'username',
    'password',
    'id'
);
$authAdapter->setIdentity($username);
$authAdapter->setCredential($password);
$auth = $authAdapter->authenticate();
// If login has been successful store credentials in session
if ($auth->isValid()) {
    $session->username = $username;
    $session->password = (string)$password;
    $id = $authAdapter->getResultRowObject();
    $session->realname = $id->real_name;
    // Rememeber session for x more minutes (see config for exact value)
    Zend_Session::rememberMe();
}

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

$plugin = new ShoppingControl_Plugin_Auth($auth);
$front->registerPlugin($plugin);

$front->dispatch();