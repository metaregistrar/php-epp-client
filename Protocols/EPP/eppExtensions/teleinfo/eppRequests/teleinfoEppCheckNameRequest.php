<?php
namespace Metaregistrar\EPP;

class teleinfoEppCheckNameRequest extends teleinfoEppNameRequest {

    function __construct($domain) {
        $this->setNamespacesinroot(false);
        parent::__construct(eppRequest::TYPE_CHECK);
        $this->setDomainName($domain);
        $this->addSessionId();
    }

    /**
     * @param string|eppChinaName $domains
     */
    public function setDomainName($domain) {
        if ($domain instanceof eppChinaName) {
            $this->nameobject->appendChild($this->createElement('nv:name', $domain->getName()));
        } else {
            $this->nameobject->appendChild($this->createElement('nv:name', $domain));
        }
    }


}