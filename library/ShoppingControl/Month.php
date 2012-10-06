<?php
require_once LIBPATH . '/ShoppingControl/Table/Abstract.php';

class ShoppingControl_Month extends ShoppingControl_Table_Abstract
{
    /**
     * Does the month already exist in the Database?
     * 
     * @var Boolean
     */
    protected $_exists = false;
    
    /**
     * Current month in string format
     * Format: YYYY-MM
     * 
     * @var String
     */
    protected $_month;

    /**
     * 
     * @param Integer $month
     */
    public function __construct($monthString)
    {
        parent::__construct();
        $this->_month = $monthString;
        $monthInDb = $this->find($this->_month);
        if ($monthInDb !== false) {
            $this->_exists = true;
            $monthArray = explode('-', $this->_month);
            $this->_monthName = strftime('%b %Y', mktime(0, 0, 0, $monthArray[1], 1, $monthArray[0]));
            $this->columnsAsProperties($monthInDb);
            $this->rest = $this->getRest();
        }
    }
    
    public function __toString()
    {
        return $this->_monthName;
    }
    
    protected function find($month)
    {
        $sql = $this->_db->select()
            ->from(array('m' => 'month'))
            ->where('m.month = ?', $month);
        $result = $this->_db->query($sql);
        return $result->fetch();
    }
    
    public function getRest()
    {
        return $this->limit - $this->spent();            
    }
    
    public function spent()
    {
        // select sum(amount) from purchase where strftime("%Y-%m") = "2010-03";
        // Read sum of this months purchases
        $sql = $this->_db->select()
            ->from(
            	'purchase',
                array(
                    'amount' => 'SUM(amount)'
                )
            )
            ->where('strftime("%Y-%m", date) = ?', $this->month);
        $query = $this->_db->query($sql);
        $result = $query->fetch();
        return (float)$result['amount'];
    }

    public static function create($configArray)
    {
        Zend_Registry::get('db')->insert('month', $configArray);
        return new self($configArray['month']);
    }
    
    private static function getYears()
    {
        $db = Zend_Registry::get('db');
        $sql = $db->select()
            ->from(
            	'purchase',
                array(
                    'year' => 'strftime("%Y", date)'
                )
            )
            ->group('year')
            ->order('year DESC');
        $query = $db->query($sql);
        $yearsFromDb = $query->fetchAll();
        $years = array();
        foreach ($yearsFromDb as $year) {
            $years[$year['year']] = array();
        }
        return $years;
    }
    
    public static function getAll()
    {
        $years = self::getYears();
        
        $db = Zend_Registry::get('db');
        $sql = $db->select()
            ->from(
            	'purchase',
                array(
                    'month' => 'strftime("%Y-%m", date)',
                	'year' => 'strftime("%Y", date)'
                )
            )
            ->group('month')
            ->order('month DESC');
        $query = $db->query($sql);
        $monthsFromDb = $query->fetchAll();
        $months = array();
        foreach($monthsFromDb as $month) {
            $years[$month['year']][] = new self($month['month']);
            $months[] = new self($month['month']);
        }
        return $years;
    }
    
    public function exists()
    {
        return $this->_exists;
    }
    
    public function getPurchases()
    {
	    $tables = array(
	        'p' => 'purchase'
	    );
	    $tablesColumns = array(
	        'p.purchase_id',
	        'date' => 'strftime("%s", p.date)',
	        'p.amount',
	        'p.comment'
	    );
	    $join = array(
	        's' => 'shop'
	    );
	    $select = $this->_db->select()
	        ->from($tables, $tablesColumns)
	        ->join(
	            $join,
	            'p.shop_id = s.shop_id'
	        )
	        ->where('strftime("%Y-%m", date) = ?', $this->_month)
	        ->order(array(
	            'date DESC'
	        ));
	    $purchases = $this->_db->query($select)->fetchAll();
	    // Format date according to config
	    // Format amount with 2 decimals
	    $config = Zend_Registry::get('config');
        foreach($purchases as $key => $purchase) {
            $purchases[$key]['date'] = date($config->general->dateformat, $purchase['date']);
            $purchases[$key]['amount'] = number_format($purchase['amount'], 2);
        }
        return $purchases;
    }
    
    public function getOverviewLink()
    {
        return URLPATH . '/overview/month/month/' . $this->_month;
    }
}