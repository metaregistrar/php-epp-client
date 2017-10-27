<?php
namespace Metaregistrar\EPP;

class ptEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));

        parent::useExtension('ptdomain-1.0');
        parent::useExtension('ptcontact-1.0');
    }

}
