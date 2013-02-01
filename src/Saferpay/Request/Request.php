<?php namespace Saferpay\Request;

use Saferpay\Response;
use Saferpay\Helper;

class RequestException extends \Exception {}

abstract class Request
{
    protected $url = '';
    protected $attributes = array();
    protected $method = 'get';
    protected $result = null;
    protected $response = null;
    private $ch = null;

    public function getAttribute($attribute)
    {
        if(isset($this->attributes[strToLower($attribute)]))
            return $this->attributes[strToLower($attribute)];
        return null;
    }

    public function setAttribute($attribute, $value = '')
    {
        $this->attributes[strToLower($attribute)] = $value;
        return $this;
    }

    public function addAttributes($attributes) {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    public function execute()
    {
        if($this->result != null) return $this->result;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_HEADER, false);

        // HTTPS
        if(strpos($this->url, 'https://') === true) {
            curl_setopt($this->ch, CURLOPT_PORT, 443);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        // POST
        if($this->method == 'post') {
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->attributes);
        } else if($this->method == 'get') {
            curl_setopt($this->ch, CURLOPT_URL, Helper::appendParamsToUrl($this->url, $this->attributes));
        }

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $this->result = curl_exec($this->ch);
        if(curl_errno($this->ch)) {
            echo curl_errno($this->ch).": ".curl_error($this->ch);
        }
        curl_close($this->ch);

        return $this->result;
    }

    public function executeAgain()
    {
        $this->result = null;
        return $this->execute();
    }

    public function getCurlErrors()
    {
        if(is_resource($this->ch)) {
            return curl_error($this->ch) . '(' . curl_errno($this->ch) . ')';
        } else {
            return 'no valid curl resource';
        }
    }

    public function result() {
        $this->execute();
        return $this->result;
    }

    public function isOk() {
        return starts_with($this->result(), 'OK');
    }

    public function hasError() {
        return starts_with($this->result(), 'ERROR');
    }

    public function ok() {
        if(starts_with($this->result(), 'OK')) {
            $response = new Response(ltrim($this->result(), 'OK:'));
            return $response->ok();
        }
        return false;
    }

    public function getResponse() {
        $msg = $this->result();
        $msg = ltrim($msg, 'OK');
        $msg = ltrim($msg, 'ERROR:');
        $msg = trim($msg);
        if(empty($msg)) return null;

        if(!starts_with($msg, '<') && !ends_with($msg, '/>')) {
            return new Response('<IDP result="'. ($this->hasError() ? 7007 : 0) .'" message="'.htmlentities($msg).'"/>');
        } else {
            return new Response($msg);
        }
    }
}
