<?php namespace Saferpay;

class PayInitResponse extends Response
{
    private function resultCode()
    {

    }

    public function ok() {
        $result = $this->resultCode();

        return $this;
    }

    public function getErrorMessage()
    {
        return ErrorCode::getMessage($this->resultCode());
    }
}
