<?php
$this->addExtension('charge', 'http://www.unitedtld.com/epp/charge-1.0');

#
# Load the CHARGE specific additions
#
include_once(dirname(__FILE__) . '/eppData/chargeEppDomain.php');

include_once(dirname(__FILE__) . '/eppResponses/chargeEppCheckDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppCheckDomainRequest', 'Metaregistrar\EPP\chargeEppCheckDomainResponse');

include_once(dirname(__FILE__) . '/eppResponses/chargeEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\chargeEppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/chargeEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/chargeEppCreateDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\chargeEppCreateDomainRequest', 'Metaregistrar\EPP\chargeEppCreateDomainResponse');
