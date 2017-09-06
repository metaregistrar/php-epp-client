<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppDeleteDomainRequest;

if ($argc <= 1) {
    echo "Usage: canceldomain.php <domainname>\n";
    echo "Please enter the domain name that must be deleted\n\n";
    die();
}

$domainname = $argv[1];

try {
// Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            canceldomain($conn, $domainname);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}



/**
 * @param $conn eppConnection
 * @param $domainname string
 * @return null
 */
function canceldomain($conn, $domainname) {
    $delete = new eppDeleteDomainRequest(new eppDomain($domainname));
    if ($response = $conn->request($delete)) {
        /* @var $response \Metaregistrar\EPP\eppDeleteResponse */
        $response->dumpContents();
    }
    return null;
}