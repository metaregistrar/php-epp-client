<?php
$bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
$reflect = new ReflectionClass($bt[1]['object']);

if ($reflect->getShortName() === 'siEppConnection') {
    $this->addExtension('registrar', 'http://www.arnes.si/xml/epp/registrar-1.0');

    include_once(dirname(__FILE__) . '/eppRequests/siEppRegistrarInfoRequest.php');
    include_once(dirname(__FILE__) . '/eppResponses/siEppRegistrarInfoResponse.php');

    $this->addCommandResponse('Metaregistrar\EPP\siEppRegistrarInfoRequest', 'Metaregistrar\EPP\siEppRegistrarInfoResponse');
} else {
    $this->addExtension('registrar', 'http://www.dns.be/xml/epp/registrar-1.0');

    include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppRegistrarInfoRequest.php');
    include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppRegistrarInfoResponse.php');

    $this->addCommandResponse('Metaregistrar\EPP\dnsbeEppRegistrarInfoRequest', 'Metaregistrar\EPP\dnsbeEppRegistrarInfoResponse');

}


