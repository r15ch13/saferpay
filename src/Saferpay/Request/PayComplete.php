<?php namespace Saferpay\Request;

class PayComplete extends Request
{
    private function __construct($data = array())
    {
        $this->addAttributes(array(
            'sppassword' => 'XAjc3Kna',
            'accountid' => '99867-94913159',
        ));
        $this->url = 'https://www.saferpay.com/hosting/PayCompleteV2.asp';
        $this->method = 'get';
    }

    public function cancel() {
        return $this->setAttribute('action', 'Cancel');
    }

    public function closeBatch() {
        return $this->setAttribute('action', 'CloseBatch');
    }

    public function settlement() {
        return $this->setAttribute('action', 'Settlement');
    }

    public static function create() {
        return new self;
    }
}
