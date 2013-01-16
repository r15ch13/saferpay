<?php namespace Saferpay;

abstract class Response
{
    private $data = null;
    private $raw_data = null;
    private $signature = '';
    private $verify;

    public function getData($attribute)
    {
        if(isset($this->data[$attribute]))
            return $this->data[$attribute];
        return null;
    }

    // <IDP MSGTYPE="InsertCardResponse" KEYID="1-0" RESULT="7000" SCDRESULT="7000" SCDDESCRIPTION="An error occurred" DESCRIPTION="An error occurred" CARDREFID="" LIFETIME="" ACCOUNTID="99867-94913159" />

    public function __construct($data, $signature = '')
    {
        $this->raw_data = $data;
        $data = Helper::parseResponseXml($this->raw_data);
        foreach ($data as $key => $value) {
            $this->data[strToLower($key)] = $value;
        }
        $this->signature = $signature;
    }

    public function __get($attribute)
    {
        $attribute = strToLower($attribute);
        if(isset($this->data[$attribute])) {
            return $this->data[$attribute];
        }
        return null;
    }

    public function verify()
    {
        $this->verify = new VerfiyPayConfirm($this->raw_data, $this->signature);
        return $this->verify->ok();
    }
}
