<?php
$this->addExtension('sk-contact-ident', 'http://www.sk-nic.sk/xml/epp/sk-contact-ident-0.2');

include_once(__DIR__ . '/eppData/sknicEppContactPostalInfo.php');
include_once(__DIR__ . '/eppRequests/sknicEppCreateContactRequest.php');
include_once(__DIR__ . '/eppRequests/sknicEppUpdateContactRequest.php');

$this->addCommandResponse('Metaregistrar\EPP\sknicEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');
$this->addCommandResponse('Metaregistrar\EPP\sknicEppUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');
