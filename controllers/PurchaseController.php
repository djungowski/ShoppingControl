<?php
require_once LIBPATH . '/ShoppingControl/Controller/Action.php';

class PurchaseController extends ShoppingControl_Controller_Action
{
    public function deleteAction()
    {
        $purchaseIds = $this->_getParam('purchase_id');
        $purchaseIdsConcat = implode(',', $purchaseIds);
        $db = Zend_Registry::get('db');
        $db->delete(
            'purchase',
            sprintf('purchase_id IN (%s)', $purchaseIdsConcat)
        );
        // Redirect us back to where we came from
        $this->_redirect($_SERVER['HTTP_REFERER']);
    }
}