<?php
require_once LIBPATH . '/ShoppingControl/Controller/Action.php';
require_once LIBPATH . '/ShoppingControl/Shop.php';
require_once ZENDPATH . '/Date.php';
require_once ZENDPATH . '/Locale/Format.php';

class DefaultController extends ShoppingControl_Controller_Action
{
    public function indexAction()
    {
        $config = Zend_Registry::get('config');
        $this->view->today = date($config->general->dateformat);
        $shop = new ShoppingControl_Shop();
        $allShops = $shop->getAll();
        $this->view->shops = $allShops;
    }

    public function newAction()
    {
        $date = $this->_getParam('date');
        // Parse date according to config
        $config = Zend_Registry::get('config');
        $zd = new Zend_Date();
        $zd->setDate($date, Zend_Locale_Format::convertPhpToIsoFormat($config->general->dateformat));
        // Save date in format 2010-03-17
        $date = $zd->toString('yyyy-MM-dd');
        
        $shop = $this->_getParam('shop');
        $newshop = $this->_getParam('newshop');
        // If $newshop is not empty, a new shop shall be created
        if (!empty($newshop)) {
            $newShopObj = new ShoppingControl_Shop();
            // Overwrite $shop with auto increment id
            $shop = $newShopObj->create($newshop);
        }
        $amount = $this->_getParam('amount');
        // Replace commas in price with dots
        $amount = str_replace(',', '.', $amount);
        $comment = $this->_getParam('comment');
        // Insert dataset
        $db = Zend_Registry::get('db');
        $db->insert(
            'purchase',
            array(
                'date' => $date,
                'shop_id' => $shop,
                'amount' => $amount,
                'comment' => $comment
            )
        );
        $this->view->success = true;
        $this->view->successMessage = Zend_Registry::get('translations')->purchasesaved;
        // At last: Do everything the index controller would have done aswell
        $this->indexAction();
    }
}