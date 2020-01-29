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

    /**
     * @return null|string
     */
    public function getDomainName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:name');
    }

    /**
     * @return eppDomain
     */
    public function getDomain() {
        return new eppDomain($this->getDomainName());
    }

    /**
     * @return null|string
     */
    public function getTransferStatus() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:trStatus');
    }

    /**
     * @return null|string
     */
    public function getTransferRequestClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reID');
    }

    /**
     * @return null|string
     */
    public function getTransferRequestDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reDate');
    }

    /**
     * @return null|string
     */
    public function getTransferExpirationDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:exDate');
    }

    /**
     * @return null|string
     */
    public function getTransferActionDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acDate');
    }

    /**
     * @return null|string
     */
    public function getTransferActionClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acID');
    }
}