<?php namespace Saferpay\Request;

class PayInit extends Request
{
    private function __construct($data = array())
    {
        $this->addAttributes(array(
            'accountid' => '99867-94913159',
            'showlanguages' => 'yes',
            'currency' => 'EUR',
            'langid' => 'en',
        ));
        $this->url = 'https://www.saferpay.com/hosting/CreatePayInit.asp';
        $this->method = 'get';
    }

    public static function create() {
        return new self;
    }
}
