<?php
class LoginController extends ShoppingControl_Controller_Action
{
    public function indexAction()
    {
        $this->view->showNavigation = false;
    }
}