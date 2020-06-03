<?php
namespace Metaregistrar\EPP;

class ficoraEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);
        
        // Specify timeout values in seconds, test interface is kind of slow
        parent::setTimeout(200);

        // Enable DNSSEC, Ficora supports this
        parent::enableDnssec();

        // Make sure services are added
        parent::setServices([
            'urn:ietf:params:xml:ns:domain-1.0' => 'domain',
            'urn:ietf:params:xml:ns:contact-1.0' => 'contact',
            'urn:ietf:params:xml:ns:host-1.0' => 'host'
        ]);

        // Add the commands and responses specific to this registry
        // Please make sure the corresponding PHP files are present!
        // Not a real extension, but okay then
        parent::useExtension('ficora');
    }

}
