<?php
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppConnection.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppRequests/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppResponses/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppData/eppIncludes.php');
include_once(dirname(__FILE__).'/metaregEppLoginRequest.php');
#
# Load the Metaregistrar specific additions
#

class metaregEppConnection extends eppConnection
{

    public function __construct($configuration = 'MetaregistrarEPP')
    {        
        $config = ConfigFactory::getConfigStore($configuration);
        parent::__construct($config->logging);
        parent::setHostname($config->address);
        parent::setPort($config->port);
        parent::setUsername($config->username);
        parent::setPassword($config->password);
        parent::setTimeout($config->timeout);
        parent::setLanguage('en');
        parent::setVersion('1.0');        
        parent::addExtension('polldata','http://www.metaregistrar.com/epp/polldata-1.0');
        parent::addExtension('command-ext','http://www.metaregistrar.com/epp/command-ext-1.0');
        parent::addExtension('ext','http://www.metaregistrar.com/epp/ext-1.0');
        parent::addDefaultNamespace('command-ext','http://www.metaregistrar.com/epp/command-ext-1.0');
        parent::addDefaultNamespace('ext','http://www.metaregistrar.com/epp/ext-1.0');
    }
	
}
