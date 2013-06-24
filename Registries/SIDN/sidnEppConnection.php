<?php
#
# Load the SIDN specific additions
#
include_once(dirname(__FILE__).'/sidnEppCreateRequest.php');
include_once(dirname(__FILE__).'/sidnEppPollRequest.php');
include_once(dirname(__FILE__).'/sidnEppPollResponse.php');
include_once(dirname(__FILE__).'/sidnEppCheckResponse.php');

class sidnEppConnection extends eppConnection
{

    public function __construct()
    {
        parent::__construct(false);
        parent::setHostname('ssl://testdrs.domain-registry.nl');
        parent::setPort(700);
        parent::setUsername('');
        parent::setPassword('');
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::addExtension('sidn-epp-ext','http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        parent::addCommandResponse('sidnEppPollRequest', 'sidnEppPollResponse');
        parent::addCommandResponse('sidnEppCreateRequest', 'eppCreateResponse');
        parent::addCommandResponse('eppCheckRequest', 'sidnEppCheckResponse');
    }

}
