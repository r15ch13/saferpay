<?php namespace Saferpay\Tests;

use Saferpay\RegisterCard;

class RegisterCardTest extends \PHPUnit_Framework_TestCase
{
    public $registercard = null;

    public function setUp()
    {
        $this->registercard = \Saferpay\RegisterCard::create();
    }

    public function testRegisterCardObjectCreated() {
        $this->assertInstanceOf('\Saferpay\RegisterCard', $this->registercard);
    }

    public function testResponsqweqweributesAreParsedCorrectly()
    {
        $registercard = \Saferpay\RegisterCard::create()
            ->successlink('http://saferpay.dev/test.php?success')
            ->faillink('http://saferpay.dev/test.php?fail');

        $result = $registercard->execute();
        $this->assertStringStartsWith('https://www.saferpay.com/vt2/Pay.aspx?DATA=', $result);
        $this->assertNotNull($result);
    }
}
