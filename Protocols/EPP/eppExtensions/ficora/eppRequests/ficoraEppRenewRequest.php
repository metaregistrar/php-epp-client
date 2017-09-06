<?php


namespace Metaregistrar\EPP;

/**
 * Class ficoraEppRenewRequest
 * @package Metaregistrar\EPP
 */
class ficoraEppRenewRequest extends eppRenewRequest
{
    private $domain;

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
     * @internal param bool $autoRenew
     * @param bool $enable
     */
    public function setAutoRenew($enable = true)
    {
        $autoRenewObject = $this->createElement('domain:autorenew');
        $autoRenewObject->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $autoRenewObject->appendChild($this->createElement('domain:name', $this->domain->getDomainname()));
        $autoRenewObject->appendChild($this->createElement('domain:value', (int)$enable));

        // dirty hack should be refactored
        $this->getElementsByTagName('renew')
            ->item(0)
            ->appendChild($autoRenewObject);

        $this->getElementsByTagName('renew')->item(0)->removeChild($this->domainobject);
    }

    /**
     * @param eppDomain $domain
     * @param null $expdate
     */
    public function setDomain(eppDomain $domain, $expdate = null)
    {
        $this->domain = $domain;
        parent::setDomain($domain, $expdate);
    }
}