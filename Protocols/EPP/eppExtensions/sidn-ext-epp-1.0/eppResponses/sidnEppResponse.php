<?php
namespace Metaregistrar\EPP;

class sidnEppResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    public function Success() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:result/@code');
        $resultcode = $result->item(0)->nodeValue;
        $success = ($resultcode[0] == '1');
        if (!$success) {
            switch ($resultcode[1]) {
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
            throw new eppException($errorstring, $resultcode);
        } else {
            return true;
        }
    }
}