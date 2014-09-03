<?php
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppConnection.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppRequests/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppResponses/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppData/eppIncludes.php');



class donutsEppConnection extends eppConnection
{

    public function __construct()
    {

        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct(false);

        // Set the hostname to the EPP server
        parent::setHostname('');

        // Set the port
        parent::setPort();

        // Set your login username
        parent::setUsername('');

        // Set your login password
        parent::setPassword('');

        // Specify timeout values in seconds
        parent::setTimeout(5);

        // Enable DNSSEC, Donuts supports this
        parent::enableDnssec();

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::setLanguage('en');
        parent::setVersion('1.0');

        // Enter the path to your certificate and the password here
        parent::enableCertification(dirname(__FILE__).'', '');

        // They have registered their own extension

    }

}
