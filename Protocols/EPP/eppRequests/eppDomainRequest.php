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
        $object = $this->createElement($type);
        $this->domainobject = $this->createElement('domain:'.$type);
        $this->setNamespace('xmlns:domain','urn:ietf:params:xml:ns:domain-1.0', $this->domainobject);
        $object->appendChild($this->domainobject);
        $this->getCommand()->appendChild($object);
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }
}