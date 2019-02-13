<?php
$this->addExtension('charge', 'http://www.unitedtld.com/epp/charge-1.0');

#
# Load the CHARGE specific additions
#
include_once(dirname(__FILE__) . '/eppResponses/chargeEppCheckDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppCheckDomainRequest', 'Metaregistrar\EPP\chargeEppCheckDomainResponse');
