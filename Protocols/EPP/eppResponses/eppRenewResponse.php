<?php
namespace Metaregistrar\EPP;

class eppRenewResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    #
    # DOMAIN RENEW RESPONSES
    #

    /**
     * @return null|string
     */
    public function getDomainName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:renData/domain:name');
    }

    /**
     * @return null|string
     */
    public function getDomainExpirationDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:renData/domain:exDate');
    }


}
