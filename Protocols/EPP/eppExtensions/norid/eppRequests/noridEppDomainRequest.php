<?php
namespace Metaregistrar\EPP;

class noridEppDomainRequest extends eppDomainRequest {

    /**
     * Norid domain extension object to add namespaces to
     * @var \DomElement
     */
    public $domainextension = null;

    function __construct($type) {
        parent::__construct($type);
    }

    protected function getDomainExtension() {
        if (is_null($this->domainextension)) {
            $this->domainextension = $this->createElement('no-ext-domain:'.$type);
            if (!$this->rootNamespaces()) {
                $this->domainextension->setAttribute('xmlns:no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');
            }
            $this->getExtension()->appendChild($this->domainextension);
        }
        
        return $this->domainextension;
    }

}