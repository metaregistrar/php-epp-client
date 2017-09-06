<?php
namespace Metaregistrar\EPP;

trait noridEppHostRequestTrait {

    /**
     * Norid host extension object to add namespaces to
     * @var \DomElement
     */
    protected $hostextension = null;

    protected function getHostExtension($type) {
        if (is_null($this->hostextension)) {
            $this->hostextension = $this->createElement('no-ext-host:'.$type);
            if (!$this->rootNamespaces()) {
                $this->hostextension->setAttribute('xmlns:no-ext-host', 'http://www.norid.no/xsd/no-ext-host-1.0');
            }
            $ext = $this->getExtension();
            /* @var \DOMElement $ext */
            $ext->appendChild($this->hostextension);
        }

        return $this->hostextension;
    }

}