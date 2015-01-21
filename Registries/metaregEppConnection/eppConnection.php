<?php
namespace Metaregistrar\EPP;
#
# Load the Metaregistrar specific additions
#
include_once(dirname(__FILE__) . '/metaregInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/metaregEppPollResponse.php');

class metaregEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging);
        // Set the hostname to the EPP server
        $settings = $this->loadSettings(dirname(__FILE__));
        parent::setHostname($settings['hostname']);
        parent::setPort($settings['port']);
        parent::setUsername($settings['userid']);
        parent::setPassword($settings['password']);
        // Specify timeout values in seconds
        parent::setTimeout(5);
        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::addCommandResponse('Metaregistrar\EPP\eppPollRequest', 'Metaregistrar\EPP\metaregEppPollResponse');
        parent::addExtension('polldata','http://www.metaregistrar.com/epp/polldata-1.0');
        parent::addExtension('command-ext','http://www.metaregistrar.com/epp/command-ext-1.0');
        parent::addExtension('ext','http://www.metaregistrar.com/epp/ext-1.0');
    }

}
