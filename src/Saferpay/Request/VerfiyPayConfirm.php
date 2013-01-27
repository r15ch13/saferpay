<?php namespace Saferpay\Request;

class VerfiyPayConfirm extends Request
{
    public function __construct($data, $signature)
    {
        $this->url = 'https://www.saferpay.com/hosting/VerifyPayConfirm.asp';
        $this->addAttributes(array(
            'data' => $data,
            'signature' => $signature,
        ));
        $this->method = 'get';
    }
}
