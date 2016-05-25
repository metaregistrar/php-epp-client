<?php
namespace Metaregistrar\EPP;

class ficoraEppCreateHostRequest extends eppCreateHostRequest {
    function __construct($createinfo)
    {
        parent::__construct($createinfo);
        // Ficora needs the xmlns attribute in the contact object
        $this->hostobject->setAttribute('xmlns:host','urn:ietf:params:xml:ns:host-1.0');
        $this->addSessionId();
    }
}