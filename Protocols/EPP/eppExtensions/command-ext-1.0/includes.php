<?php
$this->addExtension('ext','http://www.metaregistrar.com/epp/ext-1.0');
include_once(dirname(__FILE__) . '/eppData/metaregInfoDomainOptions.php');

include_once(dirname(__FILE__) . '/eppRequests/metaregInfoDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregInfoDomainRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppAuthcodeRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregEppAuthcodeRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');


include_once(dirname(__FILE__) . '/eppRequests/metaregSudoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSudoResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregSudoRequest', 'Metaregistrar\EPP\metaregSudoResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppTransferExtendedRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregEppTransferExtendedRequest', 'Metaregistrar\EPP\eppTransferResponse');
