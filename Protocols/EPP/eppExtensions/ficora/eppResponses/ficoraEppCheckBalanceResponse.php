<?php
namespace Metaregistrar\EPP;

class ficoraEppCheckBalanceResponse extends eppResponse {
    function __construct($originalrequest) {
        parent::__construct($originalrequest);
    }

    public function getBalanceAmount() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/epp:balanceamount');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    public function getBalanceDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/epp:timestamp');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }
}