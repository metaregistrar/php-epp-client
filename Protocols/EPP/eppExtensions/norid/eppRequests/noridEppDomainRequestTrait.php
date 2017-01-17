<?php
namespace Metaregistrar\EPP;

trait noridEppDomainRequestTrait {

    /**
     * Norid domain extension object to add namespaces to
     * @var \DOMElement $domainextension
     */
    protected $domainextension = null;

    /**
     * Extension for Norid-specific command types
     * @var \DOMElement $extcommand
     */
    protected $extcommand = null;
    /**
     * Extension for Norid-specific command types
     * @var \DOMElement $extcommandextension
     */
    protected $extcommandextension = null;
    /**
     * Extension for Norid-specific command types
     * @var \DOMElement $extcommanddomainextension
     */
    protected $extcommanddomainextension = null;

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

    /**
     * @return \DOMElement
     */
    protected function getExtCommand() {
        if (is_null($this->extcommand)) {
            $this->extcommand = $this->createElement('command');
            if (!$this->rootNamespaces()) {
                $this->extcommand->setAttribute('xmlns', 'http://www.norid.no/xsd/no-ext-epp-1.0');
            }
            $ext = $this->getExtension();
            /* @var \DOMElement $ext */
            $ext->appendChild($this->extcommand);

        }
        return $this->extcommand;
    }
    /**
     * @return \DOMElement
     */
    protected function getExtCommandExtension() {
        if (is_null($this->extcommandextension)) {
            $this->extcommandextension = $this->createElement('extension');
            $this->getExtCommand()->appendChild($this->extcommandextension);
        }
        
        return $this->extcommandextension;
    }

    /**
     * @param string $type
     * @return \DOMElement
     */
    protected function getExtCommandDomainExtension($type) {
        if (is_null($this->extcommanddomainextension)) {
            $this->extcommanddomainextension = $this->createElement('no-ext-domain:'.$type);
            if (!$this->rootNamespaces()) {
                $this->extcommanddomainextension->setAttribute('xmlns:no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');
            }
            $this->getExtCommandExtension()->appendChild($this->extcommanddomainextension);
        }
        
        return $this->extcommanddomainextension;
    }

}