<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dwit for example request/response

class noridEppWithdrawContactRequest extends eppRequest {

    use noridEppDomainRequestTrait;
    
    protected $domainobject = null;

    function __construct(noridEppDomain $domain, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct();
        $this->domainobject = $this->createElement('domain:withdraw');
        if (!$this->rootNamespaces()) {
            $this->domainobject->setAttribute('xmlns:no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');
        }
        $commandobject = $this->createElement('command');
        if (!$this->rootNamespaces()) {
            $commandobject->setAttribute('xmlns', 'http://www.norid.no/xsd/no-ext-epp-1.0');
        }
        $this->getEpp()->appendChild($this->createElement('extension')->appendChild($commandobject->appendChild($this->createElement('withdraw')->appendChild($this->domainobject))));
        $this->setDomain($domain);
        $this->addSessionId();
    }

    public function setDomain(noridEppDomain $domain) {
        if (!is_null($domain)) {
            $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        }
    }

}