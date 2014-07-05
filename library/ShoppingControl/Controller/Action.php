<?php
require_once LIBPATH . '/ShoppingControl/Month.php';

class ShoppingControl_Controller_Action extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->view->basepath = URLPATH;
        $this->view->translations = Zend_Registry::get('translations');
        $config = Zend_Registry::get('config');
        $this->view->currency = $config->general->currency;
        $this->view->version = SHOPPINGCONTROL_VERSION;
        $this->view->controllerName = $this->getRequest()->getControllerName();
        $this->view->actionName = $this->getRequest()->getActionName();
        $this->view->showNavigation = true;
        $session = new Zend_Session_Namespace($config->session->namespace);
        $this->view->loggedInUser = $session->realname;
        $this->view->cachebuster = md5(SHOPPINGCONTROL_VERSION);
    }
    
    public function postDispatch()
    {
        // Set in month in postDispatch so that the rest is always up-to-date
        $this->setMonth();       
    }
    
    protected function setMonth()
    {
        $config = Zend_Registry::get('config');
        $monthString = null;
        // Check if month is set manually, else get data for the current month
        if (
            $this->getRequest()->getControllerName() == 'overview' &&
            $this->getRequest()->getActionName() == 'month'   
        ) {
            $monthString = $this->_getParam('month');
        }
        if (is_null($monthString)) {
            $monthString = date('Y-m');
        }
        $month = new ShoppingControl_Month($monthString);
        $this->view->month = $monthString;
        if (!$month->exists()) {
            $this->view->monthExists = false;
            $this->view->monthLimit = $config->limit->month;
        } else {
            $this->view->monthExists = true;
            $this->view->monthLimit = $month->limit;
            $this->view->monthRest = $month->rest;
        }
    }
}