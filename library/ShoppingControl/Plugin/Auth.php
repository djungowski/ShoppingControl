<?php
class ShoppingControl_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * 
     * Enter description here ...
     * @var Zend_Auth_Result
     */
    private $_auth;
    
    public function __construct(Zend_Auth_Result $auth)
    {
        $this->_auth = $auth;
    }
    
	/**
     * @see Zend_Controller_Plugin_Abstract::preDispatch()
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (!$this->_auth->isValid()) {
            $request->setControllerName('login');
            $request->setActionName('index');
            return;
        }
        var_dump($this->_auth->getIdentity());
    }

    
}