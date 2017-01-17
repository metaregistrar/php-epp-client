<?php
namespace Metaregistrar\EPP;

trait noridEppDomainRequestTrait {

    /**
     * Norid domain extension object to add namespaces to
     * @var \DomElement $domainextension
     */
    protected $domainextension = null;

    /**
     * Extension for Norid-specific command types
     * @var \DomElement $extcommand
     * @var \DomElement $extcommandextension
     * @var \DomElement $extcommanddomainextension
     */
    protected $extcommand = null, $extcommandextension = null, $extcommanddomainextension = null;

    protected function getDomainExtension($type) {
        if (is_null($this->domainextension)) {
            $this->domainextension = $this->createElement('no-ext-domain:'.$type);
            if (!$this->rootNamespaces()) {
                $this->domainextension->setAttribute('xmlns:no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');
            }
            $this->getExtension()->appendChild($this->domainextension);
        }
        
        return $this->domainextension;
    }

    protected function getExtCommand() {
        if (is_null($this->extcommand)) {
            $this->extcommand = $this->createElement('command');
            if (!$this->rootNamespaces()) {
                $this->extcommand->setAttribute('xmlns', 'http://www.norid.no/xsd/no-ext-epp-1.0');
            }
            $this->getEpp()->appendChild($this->createElement('extension')->appendChild($this->extcommand));
        }
        return $this->extcommand;
    }

    protected function getExtCommandExtension() {
        if (is_null($this->extcommandextension)) {
            $this->extcommandextension = $this->createElement('extension');
            $this->getExtCommand()->appendChild($this->extcommandextension);
        }
        
        return $this->extcommandextension;
    }

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