<?php
namespace Metaregistrar\EPP;

class eppHostRequest extends eppRequest {

    /**
     * HostObject object to add namespaces to
     * @var \DomElement
     */
    public $hostobject = null;

    function __construct($type) {
        parent::__construct();
        $check = $this->createElement($type);
        $this->hostobject = $this->createElement('host:'.$type);
        if (!$this->rootNamespaces()) {
            $this->hostobject->setAttribute('xmlns:host','urn:ietf:params:xml:ns:host-1.0');
        }
        $check->appendChild($this->hostobject);
        $this->getCommand()->appendChild($check);
    }
}