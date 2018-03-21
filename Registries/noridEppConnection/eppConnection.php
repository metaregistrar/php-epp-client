<?php
namespace Metaregistrar\EPP;

class noridEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsFile = null) {
        // Construct the EPP connection object
        parent::__construct($logging, $settingsFile);

        // Enable DNSSEC because Norid supports this
        parent::enableDnssec();

        // Define supported EPP services
        parent::setServices(array(
            'urn:ietf:params:xml:ns:domain-1.0' => 'domain',
            'urn:ietf:params:xml:ns:contact-1.0' => 'contact',
            'urn:ietf:params:xml:ns:host-1.0' => 'host'
        ));

        // Add registry-specific EPP extensions
        parent::useExtension('no-ext-epp-1.0');
        parent::useExtension('no-ext-domain-1.1');
        parent::useExtension('no-ext-contact-1.0');
        parent::useExtension('no-ext-host-1.0');
        parent::useExtension('no-ext-result-1.0');



    }

}