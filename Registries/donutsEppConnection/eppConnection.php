<?php
namespace Metaregistrar\EPP;



class donutsEppConnection extends eppConnection
{

    public function __construct()
    {

        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct(false);
        if ($settings = $this->loadSettings(dirname(__FILE__))) {
            parent::setHostname($settings['hostname']);
            parent::setPort($settings['port']);
            parent::setUsername($settings['userid']);
            parent::setPassword($settings['password']);
        }

        // Specify timeout values in seconds
        parent::setTimeout(5);

        // Enable DNSSEC, Donuts supports this
        parent::enableDnssec();

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::setLanguage('en');
        parent::setVersion('1.0');

        // Enter the path to your certificate and the password here
        //parent::enableCertification(dirname(__FILE__).'', '');

        // They have registered their own extension

    }

}
