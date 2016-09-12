<?php

$this->addExtension('ficora','http://www.ficora.fi/epp/ficora');

include_once(dirname(__FILE__) . '/eppData/ficoraEppContactPostalInfo.php');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppCreateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');

include_once(dirname(__FILE__) . '/eppResponses/ficoraEppCheckContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppCheckContactRequest', 'Metaregistrar\EPP\ficoraEppCheckContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppCheckBalanceRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/ficoraEppCheckBalanceResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppCheckBalanceRequest', 'Metaregistrar\EPP\ficoraEppCheckBalanceResponse');

include_once(dirname(__FILE__) . '/eppResponses/ficoraEppInfoContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoContactRequest', 'Metaregistrar\EPP\ficoraEppInfoContactResponse');

