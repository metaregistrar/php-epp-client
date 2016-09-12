<?php
namespace Metaregistrar\EPP;

class eppContactRequest extends eppRequest {


    /**
     * ContactObject object to add namespaces to
     * @var \DomElement
     */
    public $contactobject = null;

    function __construct($type) {
        parent::__construct();
        $check = $this->createElement($type);
        $this->contactobject = $this->createElement('contact:'.$type);
        $this->setNamespace('xmlns:contact','urn:ietf:params:xml:ns:contact-1.0', $this->contactobject);
        $check->appendChild($this->contactobject);
        $this->getCommand()->appendChild($check);
    }
}