<?php
namespace Metaregistrar\EPP;

class ptEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));

        parent::addCommandResponse('Metaregistrar\EPP\ptEppInfoDomainRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add

        parent::addExtension('ptcontact', 'http://eppdev.dns.pt/schemas/ptcontact-1.0');
        parent::addExtension('ptdomain', 'http://eppdev.dns.pt/schemas/ptdomain-1.0');
    }

}
