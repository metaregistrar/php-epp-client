<?php
namespace Metaregistrar\EPP;

class eppDomainRequest extends eppRequest {

    /**
     * @var bool
     */
    private $forcehostattr = false;

    /**
     * DomainObject object to add namespaces to
     * @var \DomElement
     */
    public $domainobject = null;

    function __construct($type) {
        parent::__construct();
        $check = $this->createElement($type);
        $this->domainobject = $this->createElement('domain:'.$type);
        if (!$this->rootNamespaces()) {
            $this->domainobject->setAttribute('xmlns:domain','urn:ietf:params:xml:ns:domain-1.0');
        }
        $check->appendChild($this->domainobject);
        $this->getCommand()->appendChild($check);
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }
}