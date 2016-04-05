<?php

namespace Metaregistrar\EPP;

class metaregSudoResponse extends eppResponse {

    private $originalResponse;

    function __construct($originalrequest) {
        parent::__construct($originalrequest);
        /* @var originalrequest Metaregistrar\EPP\metaregSudoRequest */
        $testconn = new eppConnection;
        foreach ($testconn->getResponses() as $req => $res) {
            if ($originalrequest->getOriginalRequest() instanceof $req) {
                $this->originalResponse = new $res();
            }
        }
    }
    
    function loadXML($xml) {
        $this->originalResponse->loadXML($xml);
        return parent::loadXML($xml);
    }
    
    function getOriginalResponse() {
        return $this->originalResponse;
    }
}