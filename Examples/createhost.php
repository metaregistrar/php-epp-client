<?php
require('../autoloader.php');

if ($argc <= 1) {
    echo "Usage: createhost.php <hostname>\n";
    echo "Please a host name to create\n\n";
    die();
}

$hostname = $argv[1];

try {
// Please enter your own settings file here under before using this example
    if ($conn = Metaregistrar\EPP\eppConnection::create('../Tests/testsetup.ini')) {
        // Connect to the EPP server
        if ($conn->login()) {
            createhost($conn, $hostname);
            $conn->logout();
        }
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}



/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $hostname string
 * @return null
 */
function createhost($conn, $hostname) {
    $host = new Metaregistrar\EPP\eppHost($hostname);
    $create = new Metaregistrar\EPP\eppCreateHostRequest($host);
    if ((($response = $conn->writeandread($create)) instanceof Metaregistrar\EPP\eppCreateHostResponse) && ($response->Success())) {
        /* @var $response Metaregistrar\EPP\eppCreateHostResponse */
        echo "Host created on " . $response->getHostCreateDate() . " with id " . $response->getHostName() . "\n";
    }
    return null;
}