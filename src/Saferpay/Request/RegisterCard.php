<?php namespace Saferpay\Request;

class RegisterCard extends Request
{
    public function __construct($data = array(), $salt = '')
    {
        $cardrefid = sha1($salt.rand(0, time()).time().$salt);
        $this->addAttributes(array(
            'accountid' => '99867-94913159',
            'successlink' => '',
            'faillink' => '',
            'cardrefid' => $cardrefid,
            'langid' => 'en',
        ));
        $this->url = 'https://www.saferpay.com/hosting/CreatePayInit.asp';
        $this->method = 'get';
    }

    public static function create($salt = '') {
        return new self(array(), $salt);
    }
}
