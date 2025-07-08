<?php
namespace Metaregistrar\EPP;

class rrpproxyEppInfoDomainResponse extends eppInfoDomainResponse {
    function __construct() {
        parent::__construct();
    }

    /**
     * Get the domain renewal date.
     *
     * @return string
     */
    public function getDomainRenewalDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:infData/keysys:renDate');
    }

    /**
     * Get the domain renewal mode.
     *
     * @return string
     */
    public function getDomainRenewalMode() {
        return $this->queryPath('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:infData/keysys:renewalmode');
    }

    /**
     * Get the domain renewal mode.
     *
     * @return string
     */
    public function getDomainTransferLock() {
        return $this->queryPath('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:infData/keysys:transferlock');
    }

    /**
     * Get the domain renewal mode.
     *
     * @return string
     */
    public function getDomainTransferMode() {
        return $this->queryPath('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:infData/keysys:transfermode');
    }
}

