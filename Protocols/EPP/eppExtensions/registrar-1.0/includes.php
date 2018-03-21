<?php
$this->addExtension('registrar', 'http://www.arnes.si/xml/epp/registrar-1.0');

include_once(dirname(__FILE__) . '/eppRequests/siEppRegistrarInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/siEppRegistrarInfoResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\siEppRegistrarInfoRequest', 'Metaregistrar\EPP\siEppRegistrarInfoResponse');


