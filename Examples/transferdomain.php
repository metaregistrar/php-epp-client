<?php
require('../autoloader.php');

/*
 * This script requests a domain name transfer into your account
 */


if ($argc <= 2) {
    echo "Usage: transferdomain.php <domainname> <authcode>\n";
    echo "Please the domain name and the auth code for transfer\n\n";
    die();
}
$domainname = $argv[1];
$authcode = $argv[2];

echo "Transferring $domainname\n";
try {
    $conn = new Metaregistrar\EPP\metaregEppConnection();
    // Connect and login to the EPP server
    if ($conn->connect()) {
        if (login($conn)) {
            transferdomain($conn, $domainname, $authcode);
            logout($conn);
        }
    } else {
        echo "ERROR CONNECTING\n";
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}


function transferdomain($conn, $domainname, $authcode) {
    try {
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $domain->setAuthorisationCode($authcode);
        $transfer = new Metaregistrar\EPP\eppTransferRequest(Metaregistrar\EPP\eppTransferRequest::OPERATION_REQUEST,$domain);
        if ((($response = $conn->writeandread($transfer)) instanceof Metaregistrar\EPP\eppTransferResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppTransferResponse */
            echo $response->getDomainName()," transfer request was succesful\n";
        } else {
            echo "ERROR2\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo 'ERROR1';
        echo $e->getMessage() . "\n";
    }
}