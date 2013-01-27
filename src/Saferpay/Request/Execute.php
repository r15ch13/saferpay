<?php namespace Saferpay\Request;

class Execute extends Request
{
    private function __construct($data = array())
    {
        $this->addAttributes(array(
            'sppassword' => 'XAjc3Kna',
            'accountid' => '99867-94913159',
            'currency' => 'EUR',
        ));
        $this->url = 'https://www.saferpay.com/hosting/Execute.asp';
        $this->method = 'get';
    }

    public static function create() {
        return new self;
    }
}
