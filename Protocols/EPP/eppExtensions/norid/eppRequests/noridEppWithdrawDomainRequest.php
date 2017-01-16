<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dwit for example request/response

class noridEppWithdrawContactRequest extends eppRequest {

    use noridEppDomainRequestTrait;
    
    protected $domainobject = null;

    function __construct(noridEppDomain $domain, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct();
        $this->setDomain($domain);
        $this->addSessionId();
    }

    public function setDomain(noridEppDomain $domain) {
        $withdraw = $this->createElement('withdraw');
        $this->domainobject = $this->createElement('domain:withdraw');
        if (!$this->rootNamespaces()) {
            $this->domainobject->setAttribute('xmlns:no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');
        }
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        $withdraw->appendChild($this->domainobject);
        $this->getExtCommand()->appendChild($withdraw);
    }

}