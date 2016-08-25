<?php
namespace Metaregistrar\EPP;

class eppCheckDomainRequest extends eppDomainRequest {

    function __construct($checkrequest, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_CHECK);
        if ($checkrequest instanceof eppDomain) {
            $this->setDomainNames(array($checkrequest));
        } else {
            if (is_array($checkrequest)) {
                if ($checkrequest[0] instanceof eppDomain) {
                    $this->setDomainNames($checkrequest);
                } else {
                   if (is_string($checkrequest[0])) {
                       $this->setDomainNames($checkrequest);
                   }
                }
            }
        }
        $this->addSessionId();
    }

    /**
     *
     * @param array $domains
     */
    public function setDomainNames($domains) {
        #
        # Domain check structure
        #
        foreach ($domains as $domain) {
            if ($domain instanceof eppDomain) {
                $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
            } else {
                $this->domainobject->appendChild($this->createElement('domain:name', $domain));
            }
        }

    }


}