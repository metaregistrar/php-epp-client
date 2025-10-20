<?php
$this->addExtension('extdom-2.0', 'http://www.nic.it/ITNIC-EPP/extdom-2.0');

include_once(dirname(__FILE__) . '/eppResponses/itEppCreateDomainResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/itEppInfoDomainResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\eppCreateDomainRequest', 'Metaregistrar\EPP\itEppCreateDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\itEppInfoDomainResponse');