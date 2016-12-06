<?php

// Traits
// Requests
include_once(dirname(__FILE__) . '/eppRequests/noridEppContactRequestTrait.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppDomainRequestTrait.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppHostRequestTrait.php');
// Responses
include_once(dirname(__FILE__) . '/eppResponses/noridEppResponseTrait.php');


// Domain
include_once(dirname(__FILE__) . '/eppData/noridEppDomain.php');
// Requests
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppWithdrawDomainRequest.php');
// Responses
include_once(dirname(__FILE__) . '/eppResponses/noridEppWithdrawDomainResponse.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateDomainResponse.php');


// Contact
include_once(dirname(__FILE__) . '/eppData/noridEppContact.php');
// Requests
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppUpdateContactRequest.php');
// Responses
include_once(dirname(__FILE__) . '/eppResponses/noridEppInfoContactResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCheckContactResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCreateContactResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppUpdateContactResponse.php');


// Host
include_once(dirname(__FILE__) . '/eppData/noridEppHost.php');
// Requests
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateHostRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppUpdateHostRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppInfoHostRequest.php');
// Responses
include_once(dirname(__FILE__) . '/eppResponses/noridEppInfoHostResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCreateHostResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppUpdateHostResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppDeleteHostResponse.php');
