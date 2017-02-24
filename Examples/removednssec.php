<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppSecdns;
use Metaregistrar\EPP\eppDnssecUpdateDomainRequest;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppInfoDomainRequest;
use Metaregistrar\EPP\eppInfoDomainResponse;

try {
    $domainname = 'portugalvakanties.nl';
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->enableDnssec();
        if ($conn->login()) {
            $dnssec = infodomain($conn, $domainname);
            if (is_array($dnssec) && (count($dnssec)>0)) {
                removednssec($conn, $domainname, $dnssec);
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

/**
 * @param eppConnection $conn
 * @param $domainname
 * @param [eppSecdns] $dnssec
 */
function removednssec(eppConnection $conn, $domainname, $dnssec) {
    $domain = new eppDomain($domainname);
    $remove = new eppDomain($domainname);
    foreach ($dnssec as $secdns) {
        /* @var eppSecdns $dnssec */
        $remove->addSecdns($secdns);
    }
    $update = new eppDnssecUpdateDomainRequest($domain, null, $remove, null);
    if ($response = $conn->request($update)) {
        /* @var $response Metaregistrar\EPP\eppUpdateDomainResponse */
        echo $response->saveXML();
    }
}


/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $domainname string
 * @return string
 */
function infodomain(eppConnection $conn, $domainname) {

    $info = new eppInfoDomainRequest(new eppDomain($domainname));
    if ($response = $conn->request($info)) {
        /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
        return $response->getKeydata();

    } else {
        echo "ERROR retrieving domain info for $domainname\n";
    }

    return null;
}