<?php


namespace Metaregistrar\EPP;

/**
 * Class ficoraEppRenewRequest
 * @package Metaregistrar\EPP
 */
class ficoraEppRenewRequest extends eppRenewRequest
{
    private $autoRenew = false;

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

    /**
     * @param boolean $autoRenew
     */
    public function setAutoRenew($autoRenew)
    {
        $this->autoRenew = $autoRenew;
    }

    /**
     * @param eppDomain $domain
     * @param null $expdate
     */
    public function setDomain(eppDomain $domain, $expdate = null)
    {
        $this->domainobject->appendChild($this->createElement('domain:autorenew', (int)$this->autoRenew));

        parent::setDomain($domain, $expdate);
    }
}