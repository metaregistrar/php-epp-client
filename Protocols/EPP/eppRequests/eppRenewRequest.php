<?php
namespace Metaregistrar\EPP;

class eppRenewRequest extends eppDomainRequest {
    function __construct($domain, $expdate = null, $namespacesinroot = null) {
        if ($namespacesinroot!==null) {
            $this->setNamespacesinroot($namespacesinroot);
        } else {
            if (defined("NAMESPACESINROOT")) {
                $this->setNamespacesinroot(NAMESPACESINROOT);
            }
        }
        parent::__construct(eppRequest::TYPE_RENEW);

        #
        # Sanity checks
        #
        if (!($domain instanceof eppDomain)) {
            throw new eppException('eppRenewRequest needs valid eppDomain object as parameter');
        }
        $this->setDomain($domain, $expdate);
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setDomain(eppDomain $domain, $expdate = null) {
        #
        # Object create structure
        #
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if ($expdate) {
            $this->domainobject->appendChild($this->createElement('domain:curExpDate', $expdate));
        }
        if ($domain->getPeriod() > 0) {
            $domainperiod = $this->createElement('domain:period', $domain->getPeriod());
            $domainperiod->setAttribute('unit', $domain->getPeriodUnit());
            $this->domainobject->appendChild($domainperiod);
        }
    }
}