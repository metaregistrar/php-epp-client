<?php
namespace Metaregistrar\EPP;

class teleinfoEppNameRequest extends eppRequest {

    /**
     * @var bool
     */
    private $forcehostattr = false;

    /**
     * DomainObject object to add namespaces to
     * @var \DomElement
     */
    public $nameobject = null;

    function __construct($type) {
        parent::__construct();
        $element = $this->createElement($type);
        $this->nameobject = $this->createElement('nv:'.$type);
        if (!$this->rootNamespaces()) {
            $this->nameobject->setAttribute('xmlns:nv','urn:ietf:params:xml:ns:nv-1.0');
        }
        $element->appendChild($this->nameobject);
        $this->getCommand()->appendChild($element);
    }

    public function getForcehostattr() {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr) {
        $this->forcehostattr = $forcehostattr;
    }
}