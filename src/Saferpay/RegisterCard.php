<?php namespace Saferpay;

class RegisterCard extends Request
{
    public function __construct($data = array(), $salt = '')
    {
        $cardrefid = sha1($salt.rand(0, time()).time().$salt);
        $default_data = array(
            'cardrefid' => $cardrefid,
        );
        $this->data = array_merge($default_data, $data);
        $this->url = 'https://www.saferpay.com/hosting/CreatePayInit.asp';
        $this->method = 'get';
    }

    public static function create() {
        return new self;
    }
}
