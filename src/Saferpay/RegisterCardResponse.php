<?php namespace Saferpay;

class RegisterCardResponse extends Response
{
    private function resultCode()
    {
        return (is_null($this->scdresult) ? ( is_null($this->result) ? null : $this->result ) : $this->scdresult);
    }

    public function ok() {
        $result = $this->resultCode();

        if (is_null($result)) {
            throw new MalformedResponseException(print_r($this, true));
        } elseif ($result != 0) {

            if (!is_null($this->scddescription)) {
                $msg = $this->scddescription;
            } elseif (!is_null($this->description)) {
                $msg = $this->description;
            } else {
                $msg = null;
            }
            throw new ResponseException($msg);
        }
        return $this;
    }

    public function getErrorMessage()
    {
        return ErrorCode::getMessage($this->resultCode());
    }
}
