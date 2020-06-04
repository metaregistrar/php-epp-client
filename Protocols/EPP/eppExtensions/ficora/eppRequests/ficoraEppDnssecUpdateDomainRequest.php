<?php


namespace Metaregistrar\EPP;

/**
 * Class ficoraEppDnssecUpdateDomainRequest
 * @package Metaregistrar\EPP
 */
class ficoraEppDnssecUpdateDomainRequest extends eppDnssecUpdateDomainRequest
{
    /**
     * ficoraEppDnssecUpdateDomainRequest constructor.
     * @param $infodomain
     * @param null $hosts
     * @param bool $namespacesinroot
     * @throws \Metaregistrar\EPP\eppException
     */
    public function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null)
    {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo);
        $this->addFicoraExtension();
        $this->addSessionId();
    }

    public function addFicoraExtension(){
        $this->domainobject->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
    }
}
