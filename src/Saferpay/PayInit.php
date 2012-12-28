<?php namespace Saferpay;

class PayInit extends Request
{
    private $response = null;

    public function __construct($data)
    {
        $this->url = 'https://www.saferpay.com/hosting/CreatePayInit.asp';
        $this->data = $data;
        $this->method = 'get';
    }
}
