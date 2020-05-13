<?php
$this->addService('eppcom', 'urn:ietf:params:xml:ns:eppcom-1.0');
$this->addService('epp', 'urn:ietf:params:xml:ns:epp-1.0');
$this->addService('nv', 'urn:ietf:params:xml:ns:nv-1.0');
$this->addService('verificationCode', 'urn:ietf:params:xml:ns:verificationCode-1.0');
$this->addExtension('nv', 'urn:ietf:params:xml:ns:nv-1.0');

include_once(dirname(__FILE__) . '/eppData/eppChinaName.php');
include_once(dirname(__FILE__) . '/eppData/eppRealName.php');

include_once(dirname(__FILE__) . '/eppRequests/teleinfoEppNameRequest.php');

include_once(dirname(__FILE__) . '/eppRequests/teleinfoEppCheckNameRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/teleinfoEppCheckNameResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\teleinfoEppCheckNameRequest', 'Metaregistrar\EPP\teleinfoEppCheckNameResponse');

include_once(dirname(__FILE__) . '/eppRequests/teleinfoEppCreateNameRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/teleinfoEppCreateNameResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\teleinfoEppCreateNameRequest', 'Metaregistrar\EPP\teleinfoEppCreateNameResponse');

include_once(dirname(__FILE__) . '/eppRequests/teleinfoEppInfoNameRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/teleinfoEppInfoNameResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\teleinfoEppInfoNameRequest', 'Metaregistrar\EPP\teleinfoEppInfoNameResponse');

include_once(dirname(__FILE__) . '/eppRequests/teleinfoEppUpdateNameRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/teleinfoEppUpdateNameResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\teleinfoEppUpdateNameRequest', 'Metaregistrar\EPP\teleinfoEppUpdateNameResponse');

include_once(dirname(__FILE__) . '/eppResponses/teleinfoEppPollResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppPollRequest', 'Metaregistrar\EPP\teleinfoEppPollResponse');
