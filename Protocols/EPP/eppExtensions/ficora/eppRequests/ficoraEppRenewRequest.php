<?php


namespace Metaregistrar\EPP;

/**
 * Class ficoraEppRenewRequest
 * @package Metaregistrar\EPP
 */
class ficoraEppRenewRequest extends eppRenewRequest
{
    /**
     * ficoraEppRenewRequest constructor.
     * @param $domain
     * @param null $expdate
     * @param bool $namespacesinroot
     */
    public function __construct($domain, $expdate = null, $namespacesinroot = true)
    {
        parent::__construct($domain, $expdate, $namespacesinroot);
        $this->addFicoraExtension();
        $this->addSessionId();
    }

    private function addFicoraExtension()
    {
        $this->domainobject->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
    }
}