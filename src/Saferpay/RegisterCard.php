<?php namespace Saferpay;

class RegisterCard extends PayInit
{
    public function __construct($data, $salt = '')
    {
        if(!isset($data['cardrefid'])) {
            $data['cardrefid'] = sha1($salt.rand(0, time()).time().$salt);
        }
        parent::__construct($data);
    }
}
