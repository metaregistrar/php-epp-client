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

        // Make sure services are not added to the main EPP commands
        parent::setServices(null);

        // Not a real extension, but okay then
        parent::addExtension('ficora','http://www.ficora.fi/epp/ficora');
        // Add the commands and responses specific to this registry
        // Please make sure the corresponding PHP files are present!
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppCheckBalanceRequest', 'Metaregistrar\EPP\ficoraEppCheckBalanceResponse');
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppInfoDomainRequest', 'Metaregistrar\EPP\ficoraEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppInfoContactRequest', 'Metaregistrar\EPP\ficoraEppInfoContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\ficoraEppRenewRequest', 'Metaregistrar\EPP\eppRenewResponse');
    }

}