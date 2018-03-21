<?php
$this->addExtension('no-ext-contact', 'http://www.norid.no/xsd/no-ext-contact-1.0');

include_once(dirname(__FILE__) . '/../no-ext-domain-1.1/eppResponses/noridEppResponseTrait.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppContactRequestTrait.php');

// Contact
include_once(dirname(__FILE__) . '/eppData/noridEppContact.php');


// Contact Create/Check/Info/Update
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCreateContactResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppCreateContactRequest', 'Metaregistrar\\EPP\\noridEppCreateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppUpdateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppUpdateContactResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppUpdateContactRequest', 'Metaregistrar\\EPP\\noridEppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppResponses/noridEppInfoContactResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\eppInfoContactRequest', 'Metaregistrar\\EPP\\noridEppInfoContactResponse');

include_once(dirname(__FILE__) . '/eppResponses/noridEppCheckContactResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\eppCheckContactRequest', 'Metaregistrar\\EPP\\noridEppCheckContactResponse');

