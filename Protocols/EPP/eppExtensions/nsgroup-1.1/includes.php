<?php

$this->addExtension('nsgroup', 'http://www.eurid.eu/xml/epp/nsgroup-1.1');

include_once(dirname(__FILE__) . '/eppRequests/euridEppInfoNsgroupRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppInfoNsgroupResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\euridEppInfoNsgroupRequest', 'Metaregistrar\EPP\euridEppInfoNsgroupResponse');
