<?php
namespace Metaregistrar\EPP;

class itEppLoginResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    /**
     * Return the available credit in euros
     * @return float
     */
    public function getCredit()
    {
        return (float) $this->queryPath('/epp:epp/epp:response/epp:extension/extepp:creditMsgData/extepp:credit');
    }
}