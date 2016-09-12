<?php
namespace Metaregistrar\EPP;

class hrEppConnection extends eppConnection {
    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));
//      parent::enableDnssec();
    }
}
