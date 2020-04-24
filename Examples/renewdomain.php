<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppRenewRequest;
use Metaregistrar\EPP\eppRenewResponse;
use Metaregistrar\EPP\eppInfoDomainRequest;
use Metaregistrar\EPP\eppInfoDomainResponse;


/*
 * This script checks for the availability of domain names
 * You can specify multiple domain names to be checked
 */


$domainname = '';
echo "Renewing $domainname\n";
try {
    // Set login details for the service in the form of
    // interface=metaregEppConnection
    // hostname=ssl://epp.test2.metaregistrar.com
    // port=7443
    // userid=xxxxxxxx
    // password=xxxxxxxxx
    // Please enter the location of the file with these settings in the string location here under
    if ($conn = eppConnection::create('')) {
        // Connect and login to the EPP server
        if ($conn->login()) {
            // Renew domain name
            if (renewdomain($conn, $domainname)) {
                echo "$domainname has been renewed\n";
            } else {
                echo "$domainname renew error\n";
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn eppConnection
 * @param $domainname string
 * @return bool
 */
function renewdomain($conn, $domainname) {
    // Create request to be sent to EPP service
    $domain = new eppDomain($domainname);
    $domain->setPeriod(1);
    $domain->setPeriodUnit('Y');
    $info = new eppInfoDomainRequest($domain);
    if ($response = $conn->request($info)) {
        /* @var $response eppInfoDomainResponse */
        $expdate = date('Y-m-d',strtotime($response->getDomainExpirationDate()));
        $renew = new eppRenewRequest($domain,$expdate);
        // Write request to EPP service, read and check the results
        if ($response = $conn->request($renew)) {
            /* @var $response eppRenewResponse */
            if ($response->getResultCode()==1000) {
                return true;
            }
        }
    }
    return false;
}