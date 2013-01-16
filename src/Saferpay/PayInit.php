<?php namespace Saferpay;

class InvalidPayInitDataException extends \Exception {}
class PayInitErrorException extends \Exception {}

class PayInit extends Request
{
    private function __construct($data = array())
    {
        $default_data = array(
            'accountid' => '99867-94913159',
            'amount' => '',
            'currency' => 'EUR',
            'description' => '',
            'orderid' => '',
            'successlink' => '',
            'faillink' => '',
            'backlink' => '',
            'autoclose' => '',
            'notifyurl' => '',
            'allowcollect' => '',
            'delivery' => '',
            'cccvc' => '',
            'ccname' => '',
            'notifyaddress' => '',
            'usernotify' => '',
            'langid' => 'en',
            'recurring' => '',
            'recfreq' => '',
            'recexp' => '',
            'installment' => '',
            'instcount' => '',
            'refid' => '',
            'refoid' => '',
            'duration' => '',
            'providerset' => '',
            'bodycolor' => '',
            'headcolor' => '',
            'headlinecolor' => '',
            'menucolor' => '',
            'bodyfontcolor' => '',
            'headfontcolor' => '',
            'linkcolor' => '',
            'showlanguages' => 'yes',
            'font' => '',
        );
        $this->data = array_merge($default_data, $data);
        $this->url = 'https://www.saferpay.com/hosting/CreatePayInit.asp';
        $this->method = 'get';
    }

    public function accountid($value) {
        $this->data['accountid'] = trim($value);
        return $this;
    }

    public function amount($value) {
        $this->data['amount'] = $value * 100;
        return $this;
    }

    public function currency($value) {
        $this->data['currency'] = strtoupper($value);
        return $this;
    }

    public function description($value) {
        $this->data['description'] = trim($value);
        return $this;
    }

    public function orderid($value) {
        $this->data['orderid'] = trim($value);
        return $this;
    }

    public function successlink($value) {
        $this->data['successlink'] = trim($value);
        return $this;
    }

    public function faillink($value) {
        $this->data['faillink'] = trim($value);
        return $this;
    }

    public function backlink($value) {
        $this->data['backlink'] = trim($value);
        return $this;
    }

    public function language($value) {
        $this->data['langid'] = trim($value);
        return $this;
    }

    public function autoclose($value) {
        $this->data['autoclose'] = trim($value);
        return $this;
    }

    public function notifyurl($value) {
        $this->data['notifyurl'] = trim($value);
        return $this;
    }

    public function allowcollect($value) {
        $this->data['allowcollect'] = trim($value);
        return $this;
    }

    public function delivery($value) {
        $this->data['delivery'] = trim($value);
        return $this;
    }

    public function cccvc($value) {
        $this->data['cccvc'] = trim($value);
        return $this;
    }

    public function ccname($value) {
        $this->data['ccname'] = trim($value);
        return $this;
    }

    public function notifyaddress($value) {
        $this->data['notifyaddress'] = trim($value);
        return $this;
    }

    public function usernotify($value) {
        $this->data['usernotify'] = trim($value);
        return $this;
    }

    public function recurring($value) {
        $this->data['recurring'] = trim($value);
        return $this;
    }

    public function recfreq($value) {
        $this->data['recfreq'] = trim($value);
        return $this;
    }

    public function recexp($value) {
        $this->data['recexp'] = trim($value);
        return $this;
    }

    public function installment($value) {
        $this->data['installment'] = trim($value);
        return $this;
    }

    public function instcount($value) {
        $this->data['instcount'] = trim($value);
        return $this;
    }

    public function refid($value) {
        $this->data['refid'] = trim($value);
        return $this;
    }

    public function refoid($value) {
        $this->data['refoid'] = trim($value);
        return $this;
    }

    public function duration($value) {
        $this->data['duration'] = trim($value);
        return $this;
    }

    public function providerset($value) {
        $this->data['providerset'] = trim($value);
        return $this;
    }

    public function bodycolor($value) {
        $this->data['bodycolor'] = trim($value);
        return $this;
    }

    public function headcolor($value) {
        $this->data['headcolor'] = trim($value);
        return $this;
    }

    public function headlinecolor($value) {
        $this->data['headlinecolor'] = trim($value);
        return $this;
    }

    public function menucolor($value) {
        $this->data['menucolor'] = trim($value);
        return $this;
    }

    public function bodyfontcolor($value) {
        $this->data['bodyfontcolor'] = trim($value);
        return $this;
    }

    public function headfontcolor($value) {
        $this->data['headfontcolor'] = trim($value);
        return $this;
    }

    public function linkcolor($value) {
        $this->data['linkcolor'] = trim($value);
        return $this;
    }

    public function showlanguages($value) {
        $this->data['showlanguages'] = trim($value);
        return $this;
    }

    public function font($value) {
        $this->data['font'] = trim($value);
        return $this;
    }

    public function ok() {
        // ERROR: An Error occurred.
        $this->execute();
        if(starts_with($this->result, 'https://')) {
            return true;
        } else {
            throw new PayInitErrorException($this->result);
        }
    }

    public function execute() {
        if(!is_numeric($this->data['amount']) || strlen($this->data['amount'] * 100) > 12) throw new InvalidPayInitDataException('Amount has to be numeric[..12].');
        if(!strlen($this->data['currency']) == 3) throw new InvalidPayInitDataException('Currency has to be ISO 4217 currency code. (e.g. EUR, CHF, ...)');
        if(empty($this->data['description'])) throw new InvalidPayInitDataException('Description can not be empty.');
        if(empty($this->data['orderid'])) throw new InvalidPayInitDataException('Order Id can not be empty.');
        if(empty($this->data['successlink'])) throw new InvalidPayInitDataException('Successlink can not be empty.');
        if(empty($this->data['faillink'])) throw new InvalidPayInitDataException('Faillink can not be empty.');
        if(empty($this->data['backlink'])) throw new InvalidPayInitDataException('Backlink can not be empty.');

        return parent::execute();
    }

    public static function create() {
        return new self;
    }
}
