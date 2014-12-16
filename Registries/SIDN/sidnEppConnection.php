<?php
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppConnection.php');

#
# Load the SIDN specific additions
#
include_once(dirname(__FILE__).'/sidnEppCreateContactRequest.php');
include_once(dirname(__FILE__).'/sidnEppPollRequest.php');
include_once(dirname(__FILE__).'/sidnEppPollResponse.php');
include_once(dirname(__FILE__).'/sidnEppCheckResponse.php');
include_once(dirname(__FILE__).'/sidnEppInfoDomainResponse.php');
include_once(dirname(__FILE__).'/sidnEppRenewRequest.php');

class sidnEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        parent::__construct($logging);
        parent::setHostname('ssl://testdrs.domain-registry.nl');
        parent::setPort(700);
        parent::setUsername('');
        parent::setPassword('');
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::addExtension('sidn-epp-ext','http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        parent::enableDnssec();
        parent::addCommandResponse('sidnEppPollRequest', 'sidnEppPollResponse');
        parent::addCommandResponse('sidnEppCreateContactRequest', 'eppCreateResponse');
        parent::addCommandResponse('eppCheckRequest', 'sidnEppCheckResponse');
        parent::addCommandResponse('eppInfoDomainRequest', 'sidnEppInfoDomainResponse');
        parent::addCommandResponse('sidnEppRenewRequest', 'eppRenewResponse');
    }

}
