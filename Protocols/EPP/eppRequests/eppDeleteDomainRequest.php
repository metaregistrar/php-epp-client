<?php
namespace Metaregistrar\EPP;

class eppDeleteDomainRequest extends eppRequest {

    function __construct(eppDomain $deleteinfo) {
        parent::__construct();
         if ($deleteinfo instanceof eppDomain) {
               $this->setDomain($deleteinfo);
         } else {
            throw new eppException('parameter of eppDeleteDomainRequest must be eppDomain object');
         }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setDomain(eppDomain $domain) {
        if (!strlen($domain->getDomainname())) {
            throw new eppException('eppDeleteRequest domain object does not contain a valid domain name');
        }
        #
        # Object delete structure
        #
        $this->domainobject = $this->createElement('delete');
        $domaindelete = $this->createElement('domain:delete');
        $domaindelete->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        $this->domainobject->appendChild($domaindelete);
        $this->getCommand()->appendChild($this->domainobject);
    }

}