<?php
namespace Metaregistrar\EPP;

class fee0EppCheckdomainResponse extends eppCheckDomainResponse {
    function __construct() {
        parent::__construct();
    }

    public function getFees() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/fee:chkData/fee:cd/*');
        if ($result->length > 0) {
            if ($result->item(0)->nodeValue == 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

}