<?php

include_once(dirname(__FILE__) . '/eppRequests/atEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppCreateResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppUpdateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppUpdateContactResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppCreateDomainRequest.php');
// include_once(dirname(__FILE__) . '/eppResponses/atEppCreateResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppUpdateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppUpdateDomainResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppDeleteRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppDeleteResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppDeleteDomainRequest.php');
// Metaregistrar\EPP\eppDeleteResponse

// Metaregistrar\EPP\eppInfoDomainRequest
include_once(dirname(__FILE__) . '/eppResponses/atEppInfoContactResponse.php');

// Metaregistrar\EPP\eppInfoContactRequest
include_once(dirname(__FILE__) . '/eppResponses/atEppInfoDomainResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppTransferRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppTransferResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppUndeleteRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppUndeleteResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppWithdrawRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppWithdrawResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/atEppPollRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppPollResponse.php');
