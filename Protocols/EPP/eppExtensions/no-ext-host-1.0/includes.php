<?php
$this->addExtension('no-ext-host', 'http://www.norid.no/xsd/no-ext-host-1.0');

include_once(dirname(__FILE__) . '/eppRequests/noridEppHostRequestTrait.php');

// Host
include_once(dirname(__FILE__) . '/eppData/noridEppHost.php');

// Host Create/Info/Update/Delete
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateHostRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCreateHostResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppCreateHostRequest', 'Metaregistrar\\EPP\\noridEppCreateHostResponse');


include_once(dirname(__FILE__) . '/eppRequests/noridEppInfoHostRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppInfoHostResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppInfoHostRequest', 'Metaregistrar\\EPP\\noridEppInfoHostResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppUpdateHostRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppUpdateHostResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppUpdateHostRequest', 'Metaregistrar\\EPP\\noridEppUpdateHostResponse');

include_once(dirname(__FILE__) . '/eppResponses/noridEppDeleteHostResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\eppDeleteHostRequest', 'Metaregistrar\\EPP\\noridEppDeleteHostResponse');



