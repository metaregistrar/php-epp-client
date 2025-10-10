<?php
$this->addExtension('extcon-1.0', 'http://www.nic.it/ITNIC-EPP/extcon-1.0');

include_once(dirname(__FILE__) . '/eppData/itEppContact.php');
include_once(dirname(__FILE__) . '/eppRequests/itEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/itEppInfoContactResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\itEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoContactRequest', 'Metaregistrar\EPP\itEppInfoContactResponse');
