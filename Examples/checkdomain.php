<?php
require('../autoloader.php');

/*
 * This script checks for the availability of domain names
 * You can specify multiple domain names to be checked
 */


if ($argc <= 1) {
    echo "Usage: checkdomain.php <domainnames>\n";
    echo "Please enter one or more domain names to check\n\n";
    die();
}

for ($i = 1; $i < $argc; $i++) {
    $domains[] = $argv[$i];
}

echo "Checking " . count($domains) . " domain names\n";
try {
    $conn = new Metaregistrar\EPP\metaregEppConnection(true);
    // Set login details for the service in the form of
    // hostname=ssl://epp.test2.metaregistrar.com
    // port=7443
    // userid=xxxxxxxx
    // password=xxxxxxxxx
    $conn->setConnectionDetails('');
    // Connect and login to the EPP server
    if ($conn->login()) {
        // Check domain names
        checkdomains($conn, $domains);
        $conn->logout();
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $domains array
 */
function checkdomains($conn, $domains) {
    try {
        // Create request to be sent to EPP service
        $check = new Metaregistrar\EPP\eppCheckRequest($domains);
        // Write request to EPP service, read and check the results
        if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppCheckResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCheckResponse */
            // Walk through the results
            $checks = $response->getCheckedDomains();
            foreach ($checks as $check) {
                echo $check['domainname'] . " is " . ($check['available'] ? 'free' : 'taken') . " (" . $check['reason'] . ")\n";
            }
        } else {
            // No valid response received from EPP service
            echo "ERROR2\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        // Well-formatted error received from EPP service
        echo 'ERROR1';
        echo $e->getMessage() . "\n";
    }
}