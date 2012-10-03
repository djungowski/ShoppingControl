<?php
class LogoutController extends ShoppingControl_Controller_Action
{
    public function indexAction()
    {
        Zend_Session::expireSessionCookie();
        $this->_redirect('/');
    }
}