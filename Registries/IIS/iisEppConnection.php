<?php
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppConnection.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppRequests/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppResponses/eppIncludes.php');
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppData/eppIncludes.php');
#
# Load the IIS.SE specific additions
#
include_once(dirname(__FILE__).'/iisEppCreateContactRequest.php');
include_once(dirname(__FILE__).'/iisEppInfoDomainResponse.php');
include_once(dirname(__FILE__).'/iisEppUpdateDomainClientDeleteRequest.php');


class iisEppConnection extends eppConnection
{

    public function __construct()
    {

        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct(false);

        // Set the hostname to the EPP server
        parent::setHostname('epptestv3.iis.nu');

        // Set the port
        parent::setPort(700);

        // Set your login username
        parent::setUsername('');

        // Set your login password
        parent::setPassword('');

        // Specify timeout values in seconds
        parent::setTimeout(5);

        // Enable DNSSEC, IIS.SE supports this
        parent::enableDnssec();

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::setLanguage('en');
        parent::setVersion('1.0');

        // Enter the path to your certificate and the password here
        parent::enableCertification(dirname(__FILE__).'/cert/cacert.pem', '');

        // They have registered their own extension
        parent::addExtension('iis','urn:se:iis:xml:epp:iis-1.2');

        // Add the commands and responses specific to this registry
        // Please make sure the corresponding PHP files are present!
        parent::addCommandResponse('iisEppCreateContactRequest', 'eppCreateResponse');
        parent::addCommandResponse('eppInfoDomainRequest', 'iisEppInfoDomainResponse');
        parent::addCommandResponse('iisEppUpdateDomainClientDeleteRequest','eppUpdateResponse');
    }

}
