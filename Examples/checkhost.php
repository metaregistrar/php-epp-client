<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppCheckHostRequest;

/*
 * This script checks for the availability of nameservers
 * You can specify multiple nameservers to be checked
 */

if ($argc <= 1) {
    echo "Usage: checkhost.php <hostnames>\n";
    echo "Please enter one or more host names to check\n\n";
    die();
}

for ($i = 1; $i < $argc; $i++) {
    $hosts[] = $argv[$i];
}

echo "Checking " . count($hosts) . " host names\n";
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
            // Check domain names
            checkhosts($conn, $hosts);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $hosts array of hostnames
 */
function checkhosts($conn, $hosts) {
    // Create request to be sent to EPP service
    $check = new eppCheckHostRequest($hosts);
    // Write request to EPP service, read and check the results
    if ($response = $conn->request($check)) {
        /* @var $response Metaregistrar\EPP\eppCheckHostResponse */
        // Walk through the results
        $checks = $response->getCheckedHosts();
        foreach ($checks as $hostname=>$check) {
            echo $hostname . " is " . ($check ? 'free' : 'taken')."\n";
        }
    }
}