<?php

require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppLaunchCheckRequest;


/*
 * This script checks for the availability of domain names in a certain launchphase
 * You can specify multiple domain names to be checked
 */

if ($argc <= 1) {
    echo "Usage: checklaunchdomain.php <domainnames>\n";
    echo "Please enter one or more domain names to check\n\n";
    die();
}

for ($i = 1; $i < $argc; $i++) {
    $domains[] = $argv[$i];
}

echo "Checking " . count($domains) . " domain names\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->enableLaunchphase('claims');
        // Connect and login to the EPP server
        if ($conn->login()) {
            checkdomains($conn, $domains);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}


/**
 * @param $conn eppConnection
 * @param $domains array
 */
function checkdomains($conn, $domains) {
    try {
        $check = new eppLaunchCheckRequest($domains);
        $check->setLaunchPhase('claims');
        if ($response = $conn->request($check)) {
            /* @var $response Metaregistrar\EPP\eppLaunchCheckResponse */
            $checks = $response->getCheckedDomains();
            foreach ($checks as $check) {
                echo $check['domainname'] . " is " . ($check['available'] ? 'free' : 'taken') . " (" . $check['reason'] . ")\n";
            }
        } else {
            echo "ERROR\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}
