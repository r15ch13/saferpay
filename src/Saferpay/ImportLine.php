<?php namespace Saferpay;

class InvalidImportDataException extends \Exception {}

class ImportLine
{
    private $data = array();
    private $is_direct_credit = false;
    private $is_credit_card = false;
    private $is_card_ref_id = false;

    private function __construct($data = array()) {
        $default_data = array(
            'recordtype' => 'ICCT100',
            'terminalid' => '94913159',
            'orderid' => '',
            'pan' => '',
            'expmonth' => '',
            'expyear' => '',
            'carddata' => 'M',
            'amount' => '',
            'currency' => 'EUR',
            'authcode' => '',
            'docredit' => '0',
            'doauthor' => '1',
            'timedate' => '',
            'cvc' => '',
            'eci' => '',
            'cavv' => '',
            'xid' => '',
            'recurring' => '',
            'refid' => '',
        );
        $this->data = array_merge($default_data, $data);
        $this->timedate();
        return $this;
    }

    public function __toString() {
        if(empty($this->data['recurring']) && empty($this->data['refid'])) {
            unset($this->data['recurring']);
            unset($this->data['refid']);
        }
        if(empty($this->data['eci']) && empty($this->data['cavv']) && empty($this->data['xid'])) {
            unset($this->data['eci']);
            unset($this->data['cavv']);
            unset($this->data['xid']);
        }
        if(empty($this->data['cvc'])) {
            unset($this->data['cvc']);
        }
        return implode(',', $this->data);
    }

    public function terminal_id($value) {
        if(!is_numeric($value) || strlen($value) != 8) {
            throw new InvalidImportDataException('Terminal ID has to be numeric[8].');
        }
        $this->data['terminalid'] = $value;
        return $this;
    }

    public function order_id($value) {
        if(!is_string($value) || strlen($value) > 30) {
            throw new InvalidImportDataException('Order ID has to be alpha_numeric[..30].');
        }
        $this->data['orderid'] = $value;
        return $this;
    }

    public function direct_card($bankcode, $account_number) {
        if($this->is_credit_card) throw new InvalidImportDataException('Is already a Credit Card');
        if($this->is_card_ref_id) throw new InvalidImportDataException('Is already a Card Ref. ID');
        if(strlen($bankcode) > 8) {
            throw new InvalidImportDataException('Bankcode has to be numeric[8].');
        }
        if(strlen($account_number) > 10) {
            throw new InvalidImportDataException('Account number has to be numeric[..10].');
        }
        $this->is_direct_credit = true;
        $this->data['pan'] = sprintf(';59%s=%010s', $bankcode, $account_number);
        return $this;
    }

    public function credit_card($value, $reader = false) {
        if($this->is_direct_credit) throw new InvalidImportDataException('Is already Direct Credit');
        if($this->is_card_ref_id) throw new InvalidImportDataException('Is already a Card Ref. ID');
        if(strlen($value) > 50) {
            throw new InvalidImportDataException('Credit Card has to be alpha_numeric[..50].');
        }
        $this->is_credit_card = true;
        $this->data['pan'] = $value;
        $this->data['carddata'] = $reader ? 'A' : 'M';
        return $this;
    }

    public function card_ref_id($value) {
        if($this->is_credit_card) throw new InvalidImportDataException('Is already a Credit Card');
        if($this->is_direct_credit) throw new InvalidImportDataException('Is already a Direct Credit');
        if(strlen($value) > 40) {
            throw new InvalidImportDataException('Card Ref. ID has to be alpha_numeric[..40].');
        }
        $this->is_card_ref_id = true;
        $this->data['pan'] = 'CARDREFID:'.$value;
        return $this;
    }

    public function exp_month($value) {
        if(!is_numeric($value) || strlen($value) > 2) {
            throw new InvalidImportDataException('Exp. Month has to be numeric[..2].');
        }
        $this->data['expmonth'] = sprintf('%02s', $value);
        return $this;
    }

    public function exp_year($value) {
        if(!is_numeric($value) || (strlen($value) != 2 && strlen($value) != 4)) {
            throw new InvalidImportDataException('Exp. Year has to be numeric[2] or numeric[4].');
        }

        if(strlen($value) == 2) {
            $this->data['expyear'] = sprintf('%02s', $value);
        } else {
            $this->data['expyear'] = $value;
        }
        return $this;
    }

    public function card_reader() {
        $this->data['carddata'] = 'A';
        return $this;
    }

    public function card_manually() {
        $this->data['carddata'] = 'M';
        return $this;
    }

    public function amount($value) {
        if(!is_numeric($value) || strlen($value * 100) > 12) {
            throw new InvalidImportDataException('Amount has to be numeric[..12].');
        }
        $this->data['amount'] = $value * 100;
        return $this;
    }

    public function currency($value) {
        if(strlen($value) != 3) {
            throw new InvalidImportDataException('Currency has to be numeric[3].');
        }
        $this->data['currency'] = strToUpper($value);
        return $this;
    }

    public function do_credit() {
        $this->data['docredit'] = '1';
        return $this;
    }

    public function do_debit() {
        $this->data['docredit'] = '0';
        return $this;
    }

    public function timedate($value = 'now') {
        $this->data['timedate'] = date('YmdHis', strtotime($value));
        return $this;
    }

    public function cvc($value) {
        if(!is_numeric($value) || (strlen($value) != 3 && strlen($value) != 4)) {
            throw new InvalidImportDataException('CVC has to be numeric[3] or numeric[4].');
        }
        $this->data['cvc'] = $value;
        return $this;
    }

    public function eci($value) {
        if(!is_numeric($value) || strlen($value) != 1) {
            throw new InvalidImportDataException('ECI has to be numeric[1].');
        }
        $this->data['eci'] = $value;
        return $this;
    }

    public function cavv($value) {
        if(strlen($value) > 28) {
            throw new InvalidImportDataException('CAVV has to be alpha_numeric[28].');
        }
        $this->data['cavv'] = $value;
        return $this;
    }

    public function xid($value) {
        if(strlen($value) > 28) {
            throw new InvalidImportDataException('XID has to be alpha_numeric[28].');
        }
        $this->data['xid'] = $value;
        return $this;
    }

    public function recurring($refid) {
        if(strlen($refid) > 28) {
            throw new InvalidImportDataException('Recurring Ref. ID has to be alpha_numeric[28].');
        }
        $this->data['refid'] = $refid;
        $this->data['recurring'] = 'R';
        return $this;
    }

    public static function create() {
        return new self;
    }
}
