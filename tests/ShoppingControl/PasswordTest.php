<?php
class ShoppingControl_PasswordTest extends PHPUnit_Framework_TestCase
{
    public function testHash()
    {
        $randomSalt = 'PXrDUHZlAggTx8ieeslw';
        $password = new ShoppingControl_Password($randomSalt, 16);
        $expected = 'd4622ee1c95d795500b02d5a577bc74f68ed41a55718731abf5445b1';
        $actual = $password->hash('SomeRandomPassword');
        self::assertSame($expected, $actual);
    }
    
    public function testSaltStartsAt()
    {
        $randomSalt = 'PXrDUHZlAggTx8ieeslw';
        $password = new ShoppingControl_Password($randomSalt, 8);
        $password->saltStartsAt(5);
        $expected = 'ee1c95d700b02d5a577bc74f68ed41a55718731abf5445b1';
        $actual = $password->hash('SomeRandomPassword');
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
        $password = new ShoppingControl_Password('foobar', 9);
        $password->saltStartsAt(32);
    }
}