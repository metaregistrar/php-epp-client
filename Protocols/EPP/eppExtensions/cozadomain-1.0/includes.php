<?php
$this->addExtension('cozadomain', 'http://co.za/epp/extensions/cozadomain-1-0');

include_once(dirname(__FILE__) . '/eppRequests/cozaEppAutorenewRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/cozaEppAutorenewResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\cozaEppAutorenewRequest', 'Metaregistrar\EPP\cozaEppAutorenewResponse');
