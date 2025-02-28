<?php

namespace Metaregistrar\EPP;

/**
 * Class rrpproxyEppCreateContactResponse
 */
class rrpproxyEppCreateContactResponse extends eppCreateContactResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function getValidated() : ?int {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/epp:extension/keysys:resData/keysys:creData/keysys:validated');
    }

    public function getVerified() : ?int {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/epp:extension/keysys:resData/keysys:creData/keysys:verified');
    }

    public function getVerificationRequested() : ?int {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/epp:extension/keysys:resData/keysys:creData/keysys:verification-requested');
    }
}
