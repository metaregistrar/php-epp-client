<?php
namespace Metaregistrar\EPP;

class eppDeleteDomainRequest extends eppDomainRequest {

    function __construct(eppDomain $deleteinfo) {
        parent::__construct(eppRequest::TYPE_DELETE);
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
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
    }

}