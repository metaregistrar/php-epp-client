<?php
$this->addExtension('contact-ext', 'http://www.eurid.eu/xml/epp/contact-ext-1.3');

include_once(dirname(__FILE__) . '/eppData/euridEppContact.php');
include_once(dirname(__FILE__) . '/eppRequests/euridEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/euridEppUpdateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppInfoContactResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\euridEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');
$this->addCommandResponse('Metaregistrar\EPP\euridEppUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');



