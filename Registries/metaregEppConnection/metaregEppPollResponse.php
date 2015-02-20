<?php
namespace Metaregistrar\EPP;

class metaregEppPollResponse extends eppPollResponse {
    public function    __construct() {
        parent::__construct();
    }

    public function getDelDataName() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/polldata:polldata/polldata:resData/polldata:delData/polldata:name');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

}