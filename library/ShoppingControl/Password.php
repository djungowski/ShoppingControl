<?php
class ShoppingControl_Password
{
    /**
     * Salt 'n' Peppa
     * 
     * @var String
     */
    private $_salt;
    
    /**
     * Length the salt will be trimmed to when hashing a password
     * 
     * @var Integer
     */
    private $_saltlength;
    
    /**
     * Index of where the substr of the salt starts at
     * 
     * @var Integer
     */
    private $_saltStartsAt;
    
    public function __construct($salt, $saltlength)
    {
        $this->_salt = $salt;
        $this->_saltlength = $saltlength;
    }
    
    public function hash($password)
    {
        $salt = substr(sha1($this->_salt), $this->_saltStartsAt, $this->_saltlength);
        $hash = $salt . sha1($password);
        return $hash;
    }
    
    /**
     * Set where the salt substring starts at
     * 
     * @param Integer $index
     */
    public function saltStartsAt($index)
    {
       // If the set index + the salt length are greater than 40, throw exception
       // Why 40? Because sha1 creates hashes with length 40
       if (($this->_saltlength + $index) > 40) {
           throw new InvalidArgumentException('SaltStartsAt + Saltlength must be less or equal 40');
       }
       $this->_saltStartsAt = $index;
    }
}