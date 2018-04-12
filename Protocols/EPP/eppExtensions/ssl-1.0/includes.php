<?php
$this->addExtension('ssl','http://www.metaregistrar.com/epp/ssl-1.0');

include_once(dirname(__FILE__) . '/eppData/metaregSslValidation.php');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslCreateRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslCreateResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregSslCreateRequest', 'Metaregistrar\EPP\metaregSslCreateResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslRenewRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslRenewResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregSslRenewRequest', 'Metaregistrar\EPP\metaregSslRenewResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslInfoResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregSslInfoRequest', 'Metaregistrar\EPP\metaregSslInfoResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslDeleteRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslDeleteResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregSslDeleteRequest', 'Metaregistrar\EPP\metaregSslDeleteResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregSslReissueRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregSslReissueResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregSslReissueRequest', 'Metaregistrar\EPP\metaregSslReissueResponse');


