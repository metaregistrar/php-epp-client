<?php
include_once(dirname(__FILE__).'/../eppResponse.php');
/*
 * This object contains all the logic read the response of an EPP hello command
 */

class eppRenewResponse extends eppResponse
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
    # DOMAIN RENEW RESPONSES
    #

    public function getDomainName()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:renData/domain:name');
       return $result->item(0)->nodeValue;
    }
    public function getDomainExpirationDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:renData/domain:exDate');
       return $result->item(0)->nodeValue;
    }



}
