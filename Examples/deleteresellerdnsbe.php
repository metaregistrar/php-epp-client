<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;

$domainname = 'hartvooredegem.be';
try {
// Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->useExtension('orgext-1.0');
        // Connect to the EPP server
        if ($conn->login()) {
           echo "Logged in\n";
           removereseller($conn, $domainname);
           $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage();
}

function removereseller($conn, $domainname) {
    /* @var $conn Metaregistrar\EPP\eppConnection */
    try {
        $add = new eppDomain($domainname);
        $update = new \Metaregistrar\EPP\orgextEppUpdateDomainRequest($domainname,$add);
        $update->removeReseller();
        echo $update->saveXML();
        if ($response = $conn->request($update)) {
            /* @var $response Metaregistrar\EPP\eppUpdateDomainResponse */
            echo $response->saveXML();
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}
