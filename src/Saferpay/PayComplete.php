<?php namespace Saferpay;

class InvalidPayCompleteDataException extends \Exception {}
class PayCompleteErrorException extends \Exception {}

class PayComplete extends Request
{
    private function __construct($data = array())
    {
        $default_data = array(
            'id' => '',
            'token' => '',
            'accountid' => '99867-94913159',
            'spPassword' => 'XAjc3Kna',
            'action' => '',
        );
        $this->data = array_merge($default_data, $data);
        $this->url = 'https://www.saferpay.com/hosting/PayComplete.asp';
        $this->method = 'get';
    }

    public function id($value) {
        $this->data['id'] = $value;
        return $this;
    }

    public function token($value) {
        $this->data['token'] = $value;
        return $this;
    }

    public function account_id($value) {
        $this->data['accountid'] = $value;
        return $this;
    }

    public function password($value) {
        $this->data['spPassword'] = trim($value);
        return $this;
    }

    public function amount($value) {
        $this->data['amount'] = $value * 100;
        return $this;
    }

    /**
     * The indicated transaction shall be cancelled on the Saferpay System.
     * This function will fail if the transaction has already been processed.
     * Use the Refund action in this case.
     * Once a reservation or transaction has been successfully cancelled, it is removed from the system.
     * No further operations will be allowed on the transaction handle.
     * One a CloseBatch has been performed on an account, settled transactions cannot be cancelled anymore.
     */
    public function cancel() {
        $this->data['action'] = 'Cancel';
        return $this;
    }

    /**
     * Closes the current batch
     */
    public function close_batch() {
        $this->data['action'] = 'CloseBatch';
        return $this;
    }

    /**
     * The indicated amount shall be captured on the system.
     */
    public function settlement() {
        $this->data['action'] = 'Settlement';
        return $this;
    }

    public function execute() {
        if(empty($this->data['id'])) throw new InvalidPayCompleteDataException('Id can not be empty.');
        if(empty($this->data['accountid'])) throw new InvalidPayCompleteDataException('Account Id can not be empty.');
        if(empty($this->data['spPassword'])) throw new InvalidPayCompleteDataException('Password can not be empty.');

        return parent::execute();
    }

    public static function create() {
        return new self;
    }

    public function ok() {
        // ERROR: An Error occurred.
        $this->execute();
        if($this->result == 'OK') {
            return true;
        } else {
            throw new PayCompleteErrorException($this->result);
        }
    }
}
