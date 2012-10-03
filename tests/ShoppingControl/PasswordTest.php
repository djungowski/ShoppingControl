<?php
class ShoppingControl_PasswordTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $password = new ShoppingControl_Password('NyanCat');
        $actual = (string)$password;
        self::assertSame('NyanCat', $actual);
    }
    
    public function testToStringWithSalt()
    {
        $randomSalt = 'PXrDUHZlAggTx8ieeslw';
        $password = new ShoppingControl_Password('SomeRandomPassword');
        $password->setSalt($randomSalt, 16);
        $expected = 'd4622ee1c95d795500b02d5a577bc74f68ed41a55718731abf5445b1';
        $actual = (string)$password;
        self::assertSame($expected, $actual);
    }
    
    public function testSaltStartsAt()
    {
        $randomSalt = 'PXrDUHZlAggTx8ieeslw';
        $password = new ShoppingControl_Password('SomeRandomPassword');
        $password->setSalt($randomSalt, 8, 5);
        $expected = 'ee1c95d700b02d5a577bc74f68ed41a55718731abf5445b1';
        $actual = (string)$password;
        self::assertSame($expected, $actual);
    }
    
    /**
     * Test if exception is thrown
     * when saltstart + saltlength is > 40
     * 
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage less or equal 40
     */
    public function testInvalidSaltStartsAt()
    {
        $password = new ShoppingControl_Password('something');
        $password->setSalt('foobar', 9, 32);
    }
}