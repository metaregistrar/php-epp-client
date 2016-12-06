<?php
namespace Metaregistrar\EPP;

trait noridEppDomainRequestTrait {

    /**
     * Norid domain extension object to add namespaces to
     * @var \DomElement
     */
    protected $domainextension = null;

    protected function getDomainExtension($type) {
        if (is_null($this->domainextension)) {
            $this->domainextension = $this->createElement('no-ext-domain:'.$type);
            if (!$this->rootNamespaces()) {
                $this->domainextension->setAttribute('xmlns:no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');
            }
            $ext = $this->getExtension();
            /* @var \DOMElement $ext */
            $ext->appendChild($this->domainextension);
        }
        
        return $this->domainextension;
    }

}