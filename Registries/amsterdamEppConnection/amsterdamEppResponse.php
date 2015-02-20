<?php
namespace Metaregistrar\EPP;

class amsterdamEppResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    public function Success() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:result/@code');
        $resultcode = $result->item(0)->nodeValue;
        $success = ($resultcode{0} == '1');
        if (!$success) {
            switch ($resultcode{1}) {
                case '0':
                    $this->setProblemtype('syntax');
                    break;
                case '1':
                    $this->setProblemtype('implementation-specific');
                    break;
                case '2':
                    $this->setProblemtype('security');
                    break;
                case '3':
                    $this->setProblemtype('data management');
                    break;
                case '4':
                    $this->setProblemtype('server system');
                    break;
                case '5':
                    $this->setProblemtype('connection management');
                    break;
            }
            $resultmessage = $this->getResultMessage();
            $errorstring = "Error $resultcode: $resultmessage ";
            $value = $xpath->query('/epp:epp/epp:response/epp:result/epp:value');
            foreach ($value as $missing) {
                $errorstring .= ": " . $missing->nodeValue;
            }
            $resultreason = $this->getResultReason();
            if (strlen($resultreason)) {
                $errorstring .= '(' . $resultreason . ')';
            }
            if ($resultreason = $this->getSIDNErrorString())
            {
                $errorstring .= '(' . $resultreason . ')';
            }
            throw new eppException($errorstring, $resultcode);
        } else {
            return true;
        }
    }

    public function getSIDNErrorString() {
        $xpath = $this->xPath();
        $message = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:response/sidn-ext-epp:msg');
        if (is_object($message) && ($message->length > 0)) {
            $code = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:response/sidn-ext-epp:msg/@code');
            $field = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:response/sidn-ext-epp:msg/@field');
            return 'Error '.$code->item(0)->nodeValue.', field '.$field->item(0)->nodeValue.': '.$message->item(0)->nodeValue;
        }
        return null;
    }

}