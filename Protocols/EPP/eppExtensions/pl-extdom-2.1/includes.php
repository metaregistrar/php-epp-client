<?php

$this->addExtension('extdom-2.1', 'http://www.dns.pl/nask-epp-schema/extdom-2.1');

include_once(dirname(__FILE__) . '/eppResponses/plEppPollResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\eppPollRequest', 'Metaregistrar\EPP\plEppPollResponse');
