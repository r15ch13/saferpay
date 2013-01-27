<?php namespace Saferpay;

use Saferpay\Request\VerfiyPayConfirm;

class ResponseException extends \Exception {}

class Response
{
    private $data = null;
    private $raw_data = null;
    private $signature = '';
    private $verify;

    public function __construct($data, $signature = '')
    {
        $this->raw_data = $data;
        $data = Helper::parseResponseXml($this->raw_data);
        foreach ($data as $key => $value) {
            $this->data[strToLower($key)] = $value;
        }
        $this->signature = $signature;
    }

    public function get($attribute) {
        $attribute = strToLower($attribute);
        if(isset($this->data[$attribute])) {
            return $this->data[$attribute];
        }
        return null;
    }

    public function verify()
    {
        if(!is_null($this->verify)) return $this->verify->getResponse();
        if(empty($this->signature)) return false;

        $this->verify = new VerfiyPayConfirm($this->raw_data, $this->signature);
        return $this->verify->getResponse();
    }

    public function isOk() {
        if(is_null($this->get('result'))) return false;
        if(is_numeric($this->get('result')) && $this->get('result') == 0) return true;
        return false;
    }
}
