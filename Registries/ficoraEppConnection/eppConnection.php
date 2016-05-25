<?php
namespace Metaregistrar\EPP;

class ficoraEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);
        parent::setTimeout(200);
        // Specify timeout values in seconds
        // Enable DNSSEC, Ficora supports this
        //parent::enableDnssec();

        // Not a real extension, but okay then
        parent::addExtension('ficora','http://www.ficora.fi/epp/ficora', false);
        parent::setServices(['urn:ietf:params:xml:ns:contact-1.0'=>'contact','urn:ietf:params:xml:ns:domain-1.0'=>'domain','urn:ietf:params:xml:ns:host-1.0'=>'host']);
        //parent::setServices(['urn:ietf:params:xml:ns:domain-1.0' => 'domain']);
        parent::setServices(null);
        // Add the commands and responses specific to this registry
        // Please make sure the corresponding PHP files are present!
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');
    }

}