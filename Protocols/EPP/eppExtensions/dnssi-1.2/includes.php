<?php
$this->addExtension("dnssi", "http://www.arnes.si/xml/epp/dnssi-1.2");
#
# For use with the SI connection
#
include_once(dirname(__FILE__) . '/eppData/siEppContactPostalInfo.php');
include_once(dirname(__FILE__) . '/eppRequests/siEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/siEppCreateDomainRequest.php');

$this->addCommandResponse('Metaregistrar\EPP\siEppCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\siEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');
