<?php
require_once LIBPATH . '/ShoppingControl/Controller/Action.php';
require_once LIBPATH . '/ShoppingControl/Month.php';

class OverviewController extends ShoppingControl_Controller_Action
{
    public function indexAction()
    {
        $this->view->months = ShoppingControl_Month::getAll();
    }
    
	public function currentmonthAction()
	{
	    $month = new ShoppingControl_Month(date('Y-m'));
        $this->view->purchases = $month->getPurchases();
        $this->view->spent = number_format($month->spent(), 2);
	}
	
	public function monthAction()
	{
	    $monthParam = $this->_getParam('month');
	    if (is_null($monthParam)) {
	        $this->currentmonthAction();
	        return;
	    }
	    $month = new ShoppingControl_Month($monthParam);
	    $this->view->monthName = $month->__toString();
	    $this->view->purchases = $month->getPurchases();
	    $this->view->spent = number_format($month->spent(), 2);
	}
}