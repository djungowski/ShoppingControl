<?php
require_once LIBPATH . '/ShoppingControl/Table/Abstract.php';

class ShoppingControl_Shop extends ShoppingControl_Table_Abstract
{
    public function __construct($shopAttributes = array())
    {
        parent::__construct();
        if (!empty($shopAttributes)) {
            $this->columnsAsProperties($shopAttributes);
        }
    }

    private function findByConfigArray($configArray)
    {
        // Erst nach der ID, dann nach dem Namen suchen
        if (isset($configArray['id'])) {
            $sql = 'SELECT * FROM shop WHERE shop_id = ?';
            $result = $this->_db->fetchRow($sql, $configArray['id']);
        } else {
            $sql = 'SELECT * FROM shop WHERE name LIKE ?';
            $result = $this->_db->fetchRow($sql, $configArray['name']);
        }
        // If autocreate is activated and shop doesn't exist: Create new shop
        if ($result === false && $configArray['autocreate'] == true) {
            $this->create($configArray);
        } elseif ($result !== false) {
            $this->columnsAsProperties($result);
        }
    }

    public function create($configArray)
    {
        $sql = 'INSERT INTO shop VALUES (NULL, ?)';
        $result = $this->_db->query($sql, $configArray['name']);
        $this->findByConfigArray($configArray);
    }
    
    public function getAll()
    {
        $sql = '
        SELECT		*
        FROM		shop
        ORDER BY	name ASC
        ';
        $db = Zend_Registry::get('db');
        $shopsFromDb = $db->fetchAll($sql);
        
        // Now get the most used shop
        $mostUsed = $this->getMostUsedShopId();
        $shops = array();
        foreach ($shopsFromDb as $key => $shop) {
            $shop['mostUsed'] = false;
            if ($shop['shop_id'] == $mostUsed) {
                $shop['mostUsed'] = true;
            }
            $shops[] = new self($shop);
        }
        return $shops;
    }
    
    private function getMostUsedShopId($timeRangeDays = 30)
    {
        $timeRangeDays = (int)$timeRangeDays;
        if ($timeRangeDays <= 0) {
            $timeRangeDays = 30;
        }
        $sql = '
        SELECT		shop_id,
        			count(shop_id) AS purchases
		FROM		purchase
		WHERE		date >= DATE("NOW", "-%d day")
		GROUP BY	shop_id
		ORDER BY	purchases DESC
		LIMIT		1
		';
        // Using sprintf here because of libsqlite bug
        // See http://bugs.php.net/bug.php?id=41622
        $sql = sprintf($sql, $timeRangeDays);
        $db = Zend_Registry::get('db');
        $result = $db->fetchRow($sql);
        return $result['shop_id'];
    }
}