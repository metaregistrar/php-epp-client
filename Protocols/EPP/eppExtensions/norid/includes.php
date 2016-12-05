<?php

// Static response extensions, made dynamic by other response extensions
include_once(dirname(__FILE__) . '/eppResponses/noridEppResponse.php');


// Domain
include_once(dirname(__FILE__) . '/eppData/noridEppDomain.php');
// Requests
include_once(dirname(__FILE__) . '/eppRequests/noridEppDomainRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppWithdrawDomainRequest.php');
// Responses
include_once(dirname(__FILE__) . '/eppResponses/noridEppWithdrawDomainResponse.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateDomainResponse.php');


// Contact
include_once(dirname(__FILE__) . '/eppData/noridEppContact.php');
// Requests
include_once(dirname(__FILE__) . '/eppRequests/noridEppContactRequest.php');
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
include_once(dirname(__FILE__) . '/eppRequests/noridEppHostRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateHostRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppUpdateHostRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppInfoHostRequest.php');
// Responses
include_once(dirname(__FILE__) . '/eppResponses/noridEppInfoHostResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCreateHostResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppUpdateHostResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppDeleteHostResponse.php');
