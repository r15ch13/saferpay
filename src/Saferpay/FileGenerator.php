<?php namespace Saferpay;

class FileGenerator
{
    private $file = '';
    private $data = '';
    private $sequence = '';

    private function __construct($customer_id, $path, $sequence) {
        $this->sequence = $sequence;
        $this->file = sprintf('%s%s%s-%s.IN', realpath($path), DIRECTORY_SEPARATOR, $customer_id, $this->sequence);
    }

    public static function create($customer_id, $path, $sequence) {
        return new self($customer_id, $path, $sequence);
    }

    public function add($line) {
        $this->data .= $line.PHP_EOL;
        return $this;
    }

    public function output() {
        return $this->data;
    }

    public function file() {
        return $this->file;
    }

    public function sequence() {
        return $this->sequence;
    }

    public function save() {
        return (bool)file_put_contents($this->file(), $this->output());
    }
}
