<?php namespace Saferpay\Tests;

use Saferpay\RegisterCard;

class RegisterCardTest extends \PHPUnit_Framework_TestCase
{
    public $registercard = null;

    public function setUp()
    {
        $this->registercard = \Saferpay\Request\RegisterCard::create();
    }

    public function testRegisterCardObjectCreated() {
        $this->assertInstanceOf('\Saferpay\Request\RegisterCard', $this->registercard);
    }

    public function testResponsqweqweributesAreParsedCorrectly()
    {
        $this->registercard
            ->setAttribute('successlink', 'http://saferpay.dev/test.php?success')
            ->setAttribute('faillink', 'http://saferpay.dev/test.php?fail');

        $result = $this->registercard->execute();
        $this->assertStringStartsWith('https://www.saferpay.com/vt2/Pay.aspx?DATA=', $result);
        $this->assertNotNull($result);
    }
}
