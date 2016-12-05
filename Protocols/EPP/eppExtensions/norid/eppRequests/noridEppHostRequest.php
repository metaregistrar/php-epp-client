<?php
namespace Metaregistrar\EPP;

class noridEppHostRequest extends eppHostRequest {

    /**
     * Norid host extension object to add namespaces to
     * @var \DomElement
     */
    public $hostextension = null;

    function __construct($type) {
        parent::__construct($type);
    }

    protected function getHostExtension() {
        if (is_null($this->hostextension)) {
            $this->hostextension = $this->createElement('no-ext-host:'.$type);
            if (!$this->rootNamespaces()) {
                $this->hostextension->setAttribute('xmlns:no-ext-host', 'http://www.norid.no/xsd/no-ext-host-1.0');
            }
            $this->getExtension()->appendChild($this->hostextension);
        }

        return $this->hostextension;
    }

}