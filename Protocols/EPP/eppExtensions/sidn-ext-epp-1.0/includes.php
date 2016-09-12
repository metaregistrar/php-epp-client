<?php
$this->addExtension('sidn-ext-epp','http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');

include_once(dirname(__FILE__) . '/eppResponses/sidnEppResponse.php');


include_once(dirname(__FILE__) . '/eppRequests/sidnEppCreateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\sidnEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/sidnEppRenewRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\sidnEppRenewRequest', 'Metaregistrar\EPP\eppRenewResponse');

include_once(dirname(__FILE__) . '/eppRequests/sidnEppPollRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/sidnEppPollResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\sidnEppPollRequest', 'Metaregistrar\EPP\sidnEppPollResponse');


include_once(dirname(__FILE__) . '/eppResponses/sidnEppCheckResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppCheckDomainRequest', 'Metaregistrar\EPP\sidnEppCheckResponse');

include_once(dirname(__FILE__) . '/eppResponses/sidnEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\sidnEppInfoDomainResponse');
