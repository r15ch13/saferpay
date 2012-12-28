<?php namespace Saferpay;

class VerfiyPayConfirm extends Request
{
    public function __construct($data, $signature)
    {
        $this->url = 'https://www.saferpay.com/hosting/VerifyPayConfirm.asp';
        $this->data = array(
            'data' => $data,
            'signature' => $signature,
        );
        $this->method = 'get';
    }

    public function ok() {
        // OK:RESULT=0&CARDREFID=3a124303f3e1769304dbf688ac42d61f4912274a
        // OK:RESULT=63&CARDREFID=
        // ERROR: An Error occurred.
        $this->execute();
        return Helper::string_starts_with('OK:', $this->result);
    }
}
