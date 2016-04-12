<?php

namespace Metaregistrar\EPP;

/**
 * Class metaregSudoResponse
 * @package Metaregistrar\EPP
 */
class metaregSudoResponse extends eppResponse
{
    /**
     * @var eppResponse
     */
    private $originalResponse;

    /**
     * metaregSudoResponse constructor.
     *
     * @param metaregSudoRequest $originalrequest
     */
    function __construct(metaregSudoRequest $originalrequest)
    {
        parent::__construct($originalrequest);
        $tmpConn = new eppConnection;
        $this->originalResponse = $tmpConn->createResponse(
            $originalrequest->getOriginalRequest()
        );
    }

    /**
     * @inheritdoc
     */
    function loadXML($xml, $options = NULL)
    {
        $this->originalResponse->loadXML($xml, $options);
        return parent::loadXML($xml);
    }

    /**
     * @return eppResponse
     */
    function getOriginalResponse()
    {
        return $this->originalResponse;
    }
}
