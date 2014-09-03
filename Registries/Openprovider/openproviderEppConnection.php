<?php
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppHttpsConnection.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppRequests/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppResponses/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppData/eppIncludes.php');


class openproviderEppConnection extends eppHttpsConnection
{

    public function __construct($logging=false)
    {

        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging);

        // Set the hostname to the EPP server
        parent::setHostname('epp.openprovider.eu');

        // Set the port
        parent::setPort(443);

        // Set your login username
        parent::setUsername('metaregistrar');

        // Set your login password
        parent::setPassword('quYbs5dj0');

        // Specify timeout values in seconds
        parent::setTimeout(5);

        // Enable DNSSEC, Openprovider supports this
        //parent::enableDnssec();

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::setLanguage('en');
        parent::setVersion('1.0');
	parent::addExtension('extURI','http://www.openprovider.nl/epp/xml/opprov-1.0');

	// Host objects are not supported
	parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0'=>'domain','urn:ietf:params:xml:ns:contact-1.0'=>'contact'));

    }

}


