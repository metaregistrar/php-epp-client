<?php
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppConnection.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppRequests/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppResponses/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppData/eppIncludes.php');
#
# Load the Metaregistrar specific additions
#

class metaregEppConnection extends eppConnection
{

    public function __construct()
    {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct(false);
        // Set the hostname to the EPP server
        parent::setHostname('ssl://epp.metaregistrar.com');
        // Set the port
        parent::setPort(7000);
        // Set your login username
        parent::setUsername('');
        // Set your login password
        parent::setPassword('');
        // Specify timeout values in seconds
        parent::setTimeout(5);
        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::setLanguage('en');
        parent::setVersion('1.0');        
        parent::addExtension('http://www.metaregistrar.com/epp/polldata-1.0', 'polldata');
        parent::addExtension('http://www.metaregistrar.com/epp/command-ext-1.0', 'command-ext');
        parent::addExtension('http://www.metaregistrar.com/epp/ext-1.0', 'ext');
        //parent::addDefaultNamespace('http://www.metaregistrar.com/epp/command-ext-1.0', 'command-ext');
        //parent::addDefaultNamespace('ext','http://www.metaregistrar.com/epp/ext-1.0');
    }
	
}
