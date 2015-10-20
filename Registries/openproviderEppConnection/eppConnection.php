<?php
namespace Metaregistrar\EPP;

class openproviderEppConnection extends eppHttpsConnection {

    public function __construct($logging = false, $settingsfile = null) {

        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Enable DNSSEC, Openprovider supports this
        //parent::enableDnssec();

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::addExtension('extURI', 'http://www.openprovider.nl/epp/xml/opprov-1.0');

        // Host objects are not supported
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));

    }

}


