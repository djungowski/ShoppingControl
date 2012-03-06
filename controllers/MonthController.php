<?php
require_once LIBPATH . '/ShoppingControl/Controller/Action.php';
require_once LIBPATH . '/ShoppingControl/Shop.php';
require_once LIBPATH . '/ShoppingControl/Month.php';

class MonthController extends ShoppingControl_Controller_Action
{
    public function createlimitAction()
    {
        $monthString = $this->_getParam('month');
        $limit = (int)$this->_getParam('limit');
        $month = ShoppingControl_Month::create(
            array(
                'month' => $monthString,
                'limit' => $limit
            )
        );
        $config = Zend_Registry::get('config');
        $this->view->success = true;
        $this->view->successMessage = sprintf(
            Zend_Registry::get('translations')->month->limitcreated,
            $month->limit,
            $config->general->currency
        );
        
        $forwardController = $this->_getParam('forward-controller');
        $forwardAction = $this->_getParam('forward-action');
        if (!is_null($forwardController) && !is_null($forwardAction)) {
            $this->_forward($forwardAction, $forwardController);
        } else {
            $this->_forward('index', 'index');
        }
    }
}