<?php namespace Saferpay;

abstract class Request
{
    protected $url = '';
    protected $data = array();
    protected $method = 'get';
    protected $result = null;
    protected $response = null;
    private $ch = null;

    public function getData($attribute)
    {
        if(isset($this->data[$attribute]))
            return $this->data[$attribute];
        return null;
    }

    public function setData($attribute, $value = '')
    {
        $this->data[$attribute] = $value;
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
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
        } else if($this->method == 'get') {
            curl_setopt($this->ch, CURLOPT_URL, Helper::appendParamsToUrl($this->url, $this->data));
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

    public function getErrors()
    {
        if(is_resource($this->ch)) {
            return curl_error($this->ch) . '(' . curl_errno($this->ch) . ')';
        } else {
            return 'no valid curl resource';
        }
    }

    public function result() {
        return $this->result;
    }

}
