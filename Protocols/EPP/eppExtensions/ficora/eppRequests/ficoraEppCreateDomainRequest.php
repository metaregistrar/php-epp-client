<?php
namespace Metaregistrar\EPP;

class ficoraEppCreateDomainRequest extends eppCreateDomainRequest {
    function __construct($createinfo, $forcehostattr=null)
    {
        parent::__construct($createinfo, $forcehostattr);
        // Ficora needs the xmlns attribute in the contact object
        $this->domainobject->setAttribute('xmlns:domain','urn:ietf:params:xml:ns:domain-1.0');
        $this->addSessionId();
    }
}