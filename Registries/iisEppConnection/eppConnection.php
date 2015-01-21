<?php
namespace Metaregistrar\EPP;
#
# Load the IIS.SE specific additions
#
include_once(dirname(__FILE__).'/iisEppCreateContactRequest.php');
include_once(dirname(__FILE__).'/iisEppInfoDomainResponse.php');
include_once(dirname(__FILE__).'/iisEppUpdateDomainClientDeleteRequest.php');


class iisEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging);

        // Load settings from settings.ini
        $settings = $this->loadSettings(dirname(__FILE__));

        // Set the hostname to the EPP server
        parent::setHostname($settings['hostname']);
        // Set the port
        parent::setPort($settings['port']);
        // Set your login username
        parent::setUsername($settings['userid']);
        // Set your login password
        parent::setPassword($settings['password']);
        // Specify timeout values in seconds
        parent::setTimeout(5);
        // Enable DNSSEC, IIS.SE supports this
        parent::enableDnssec();

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::setLanguage('en');
        parent::setVersion('1.0');

        // Enter the path to your certificate and the password here
        parent::enableCertification(dirname(__FILE__).'/'.$settings['certificatefile'], $settings['certificatepassword']);

        // They have registered their own extension
        parent::addExtension('iis','urn:se:iis:xml:epp:iis-1.2');

        // Add the commands and responses specific to this registry
        // Please make sure the corresponding PHP files are present!
        parent::addCommandResponse('Metaregistrar\EPP\iisEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\iisEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\iisEppUpdateDomainClientDeleteRequest','Metaregistrar\EPP\eppUpdateResponse');
    }

}