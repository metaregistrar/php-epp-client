<?php
// This extension allows the CRUD operations on a registry reseller object
//https://datatracker.ietf.org/doc/html/rfc8543
$this->addExtension('org', 'urn:ietf:params:xml:ns:epp:org-1.0');

include_once(dirname(__FILE__) . '/eppRequests/orgEppInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/orgEppInfoResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\orgEppInfoRequest', 'Metaregistrar\EPP\orgEppInfoResponse');