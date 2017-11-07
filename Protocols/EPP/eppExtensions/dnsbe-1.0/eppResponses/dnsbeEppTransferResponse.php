<?php
namespace Metaregistrar\EPP;

class dnsbeEppTransferResponse extends eppTransferResponse {

    function __construct() {
        parent::__construct();
    }

    /**
     *
     * @return string
     */
    public function getMsg() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/dnsbe:ext/dnsbe:result/dnsbe:msg');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

}

