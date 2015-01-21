<?php
namespace Metaregistrar\EPP;
#
# Load the SIDN specific additions
#
include_once(dirname(__FILE__) . '/sidnEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/sidnEppPollRequest.php');
include_once(dirname(__FILE__) . '/sidnEppPollResponse.php');
include_once(dirname(__FILE__) . '/sidnEppCheckResponse.php');
include_once(dirname(__FILE__) . '/sidnEppInfoDomainResponse.php');
include_once(dirname(__FILE__) . '/sidnEppRenewRequest.php');

class sidnEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        parent::__construct($logging);
        $settings = $this->loadSettings(dirname(__FILE__));
        parent::setHostname($settings['hostname']);
        parent::setPort($settings['port']);
        parent::setUsername($settings['userid']);
        parent::setPassword($settings['password']);
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::addExtension('sidn-epp-ext','http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        parent::enableDnssec();
        parent::addCommandResponse('Metaregistrar\EPP\sidnEppPollRequest', 'Metaregistrar\EPP\sidnEppPollResponse');
        parent::addCommandResponse('Metaregistrar\EPP\sidnEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppCheckRequest', 'Metaregistrar\EPP\sidnEppCheckResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\sidnEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\sidnEppRenewRequest', 'Metaregistrar\EPP\eppRenewResponse');
    }

}
