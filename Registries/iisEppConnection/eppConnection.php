<?php
namespace Metaregistrar\EPP;

class iisEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Specify timeout values in seconds
        // Enable DNSSEC, IIS.SE supports this
        parent::enableDnssec();

        // They have registered their own extension
        parent::addExtension('iis', 'urn:se:iis:xml:epp:iis-1.2');

        // Add the commands and responses specific to this registry
        // Please make sure the corresponding PHP files are present!
        parent::addCommandResponse('Metaregistrar\EPP\iisEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\iisEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\iisEppUpdateDomainClientDeleteRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');
    }

}