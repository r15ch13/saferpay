<?php namespace Saferpay\Tests;

use Saferpay\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseXmlAttributesAreParsedCorrectly()
    {
        $attributes = Helper::parseResponseXml('<bla bla="qwe" />');
        $this->assertEquals($attributes, array('bla' => 'qwe'));
    }

    public function testResponseXmlAttributesIsEmptyIfXmlIsMalformed()
    {
        $attributes = Helper::parseResponseXml('<bla bla="qwe" />a');
        $this->assertEquals($attributes, array());
    }
}
