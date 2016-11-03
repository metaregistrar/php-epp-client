<?php
namespace Metaregistrar\EPP;

class metaregEppConnection extends eppConnection {
    
    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::addCommandResponse('Metaregistrar\EPP\eppPollRequest', 'Metaregistrar\EPP\metaregEppPollResponse');
        parent::addCommandResponse('Metaregistrar\EPP\metaregSudoRequest', 'Metaregistrar\EPP\metaregSudoResponse');
        parent::addCommandResponse('Metaregistrar\EPP\metaregInfoDomainRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\metaregEppAuthcodeRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\metaregEppTransferExtendedRequest', 'Metaregistrar\EPP\eppTransferResponse');
        parent::addExtension('polldata', 'http://www.metaregistrar.com/epp/polldata-1.0');
        parent::addExtension('command-ext', 'http://www.metaregistrar.com/epp/command-ext-1.0');
        parent::addExtension('command-ext-domain', 'http://www.metaregistrar.com/epp/command-ext-domain-1.0');
        parent::addExtension('ext', 'http://www.metaregistrar.com/epp/ext-1.0');
        $this->enableDnssec();
        $this->enableRgp();
        //parent::enableLaunchphase('claims');
    }

}
