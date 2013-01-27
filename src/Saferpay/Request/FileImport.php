<?php namespace Saferpay\Request;

class FileImport extends Request
{
    private $parsedResult = '';

    public function __construct($file, $customerid = '99867', $username = 'e99867001', $password = 'XAjc3Kna')
    {
        $this->url = 'https://www.saferpay.com/user/FileImport/ScriptUpload.aspx';
        $this->data = array(
            'CustomerID' => $customerid,
            'spUsername' => $username,
            'spPassword' => $password,
            'UAFState'   => 'login',
            'file'       => $this->getCurlFilename($file),
        );
        $this->method = 'post';
    }

    private function getCurlFilename($file)
    {
        return sprintf('@%s;filename=%s', realpath($file), basename($file));
    }

    public function executeWorkaround()
    {
        // Workaround wenn es gar nicht gehen sollte
        $cmd = sprintf("curl -F 'spUsername=%s' -F 'spPassword=%s' -F 'UAFState=login' -F 'CustomerID=%s' -F 'file=@%s' %s",
            $this->username,
            $this->password,
            $this->customerid,
            $this->file,
            $this->url
        );
        $this->result = array();
        exec($cmd, $this->result);
        $this->result = join('', $this->result);
        return $this->result;
    }

    public function execute()
    {

        parent::execute();
        $this->parsedResult = $this->parseResult($this->result);

        if($this->parsedResult == 'WRONG_CUSTOMERID') {
            $this->executeWorkaround();
        }

        return $this->result;
    }

    public function ok()
    {
        $this->execute();
        return $this->parsedResult == 'OK';
    }

    public function result()
    {
        $this->execute();
        return $this->parsedResult;
    }

    private function parseResult($result)
    {
        if(preg_match('/<span id=\"result\">(.*)<\/span>/', $result, $match)) {
            return @array_pop($match);
        }
        return null;
    }
}
