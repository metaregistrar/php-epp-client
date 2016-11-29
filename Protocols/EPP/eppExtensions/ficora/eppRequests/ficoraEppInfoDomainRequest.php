<?php


namespace Metaregistrar\EPP;

/**
 * Class ficoraEppInfoDomainRequest
 * @package Metaregistrar\EPP
 */
class ficoraEppInfoDomainRequest extends eppInfoDomainRequest
{
    /**
     * ficoraEppInfoDomainRequest constructor.
     * @param $infodomain
     * @param null $hosts
     * @param bool $namespacesinroot
     * @throws \Metaregistrar\EPP\eppException
     */
    public function __construct($infodomain, $hosts = null, $namespacesinroot = true)
    {
        parent::__construct($infodomain, $hosts, $namespacesinroot);
        $this->addFicoraExtension();
        $this->addSessionId();
    }

    public function addFicoraExtension(){
        $this->domainobject->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
    }
}