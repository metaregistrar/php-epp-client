<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=ddel for example request/response

class noridEppDeleteDomainRequest extends eppDeleteDomainRequest {

    use noridEppDomainRequestTrait;

    function __construct(noridEppDomain $domain, $namespacesinroot = true) {
        parent::__construct($domain, $namespacesinroot);
        $this->setExtDomain($domain);
        $this->addSessionId();
    }

    public function setExtDomain(noridEppDomain $domain) {
        // Add optional dates for deletion from DNS and/or registry
        $this->setExtDomainDeleteFromDNS($domain);
        $this->setExtDomainDeleteFromRegistry($domain);
    }

    private function setExtDomainDeleteFromDNS(noridEppDomain $domain) {
        if ($date = $domain->getExtDeleteFromDNS()) {
            $dateElement = $this->createElement('no-ext-domain:deleteFromDNS', date('Y-m-d', $date));
            $this->getExtDomainExtension('delete')->appendChild($dateElement);
        }
    }

    private function setExtDomainDeleteFromRegistry(noridEppDomain $domain) {
        if ($date = $domain->getExtDeleteFromRegistry()) {
            $dateElement = $this->createElement('no-ext-domain:deleteFromRegistry', date('Y-m-d', $date));
            $this->getExtDomainExtension('delete')->appendChild($dateElement);
        }
    }

}