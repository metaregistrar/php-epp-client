<?php
// This extension allows the CRUD operations on a registry reseller object
// https://datatracker.ietf.org/doc/html/rfc8543
$this->addExtension('org', 'urn:ietf:params:xml:ns:epp:org-1.0');

include_once(dirname(__FILE__) . '/eppRequests/orgEppCheckRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/orgEppCheckResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\orgEppCheckRequest', 'Metaregistrar\EPP\orgEppCheckResponse');


include_once(dirname(__FILE__) . '/eppRequests/orgEppInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/orgEppInfoResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\orgEppInfoRequest', 'Metaregistrar\EPP\orgEppInfoResponse');


include_once(dirname(__FILE__) . '/eppRequests/orgEppCreateRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/orgEppCreateResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\orgEppCreateRequest', 'Metaregistrar\EPP\orgEppCreateResponse');

include_once(dirname(__FILE__) . '/eppRequests/orgEppUpdateRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\orgEppUpdateRequest', 'Metaregistrar\EPP\orgEppResponse');

include_once(dirname(__FILE__) . '/eppRequests/orgEppDeleteRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\orgEppDeleteRequest', 'Metaregistrar\EPP\eppDeleteResponse');
