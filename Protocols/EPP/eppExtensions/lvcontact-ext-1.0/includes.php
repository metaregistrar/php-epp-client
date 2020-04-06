<?php
$this->addExtension('lvcontact-ext-1.0', 'http://www.nic.lv/epp/schema/lvcontact-ext-1.0');

include_once(dirname(__FILE__) . '/eppData/lvEppContact.php');

include_once(dirname(__FILE__) . '/eppRequests/lvEppCreateContactRequest.php');

$this->addCommandResponse('Metaregistrar\EPP\lvEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/lvEppUpdateContactRequest.php');

$this->addCommandResponse('Metaregistrar\EPP\lvEppUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppResponse/lvEppInfoContactResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\eppInfoContactRequest', 'Metaregistrar\EPP\lvEppInfoContactResponse');

