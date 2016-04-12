<?php

namespace Metaregistrar\EPP;

class metaregSudoResponse extends eppResponse {

    /**
     * @var eppResponse
     */
    private $originalResponse;

    /**
     * metaregSudoResponse constructor.
     * @param metaregSudoRequest $originalrequest
     */
    function __construct(metaregSudoRequest $originalrequest) {
        parent::__construct($originalrequest);
        $tmpConn = new eppConnection;
        $this->originalResponse = $tmpConn->createResponse($originalrequest->getOriginalRequest());
    }

    function loadXML($xml, $options = NULL)
    {
        $this->originalResponse->loadXML($xml);
        return parent::loadXML($xml);
    }
    
    function getOriginalResponse() {
        return $this->originalResponse;
    }
}