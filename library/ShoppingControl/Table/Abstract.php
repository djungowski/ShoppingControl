<?php
abstract class ShoppingControl_Table_Abstract
{
    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;
    
    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->_db = Zend_Registry::get('db');
    }
    
    /**
     * Save columns from a SQL result set as properties
     * 
     * @param Arrays $resultArray
     */
    protected function columnsAsProperties ($resultArray)
    {
        foreach ($resultArray as $key => $value) {
            $this->$key = $value;
        }
    }
}