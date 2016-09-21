<?php
namespace Metaregistrar\EPP;

class eppTransferResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    #
    # DOMAIN TRANSFER RESPONSES
    #

    public function getDomainName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:name');
    }

    public function getDomain() {
        $return = new eppDomain($this->getDomainName());
        return $return;

    }

    public function getTransferStatus() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:trStatus');
    }

    public function getTransferRequestClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reID');
    }

    public function getTransferRequestDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reDate');
    }

    public function getTransferExpirationDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:exDate');
    }

    public function getTransferActionDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acDate');
    }

    public function getTransferActionClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acID');
    }
}