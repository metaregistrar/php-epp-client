<?php

class eppTransferResponse extends eppResponse
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    #
    # DOMAIN TRANSFER RESPONSES
    #

    public function getDomainName()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:name');
       return $result->item(0)->nodeValue;
    }

    public function getDomain()
    {
        $return = new eppDomain($this->getDomainName());
        return $return;

    }

    public function getTransferStatus()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:trStatus');
       return $result->item(0)->nodeValue;
    }

    public function getTransferRequestClientId()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reID');
       return $result->item(0)->nodeValue;
    }

    public function getTransferRequestDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reDate');
       return $result->item(0)->nodeValue;
    }

    public function getTransferExpirationDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:exDate');
       return $result->item(0)->nodeValue;
    }

    public function getTransferActionDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acDate');
       return $result->item(0)->nodeValue;
    }

    public function getTransferActionClientId()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acID');
       return $result->item(0)->nodeValue;
    }
}