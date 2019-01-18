<?php
require('../autoloader.php');

try {
    $conn = new Metaregistrar\EPP\metaregEppConnection();
    $conn->setHostname('ssl://eppltest1.metaregistrar.com'); // Hostname may vary depending on the registry selected
    $conn->setPort(7000); // Port may vary depending on the registry selected
    $conn->setUsername('');
    $conn->setPassword('');
    // Connect to the EPP server
    if ($conn->login()) {
        echo "Logged in\n";
        $conn->logout();
        echo "Logged out\n";
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo $e->getMessage() . "\n";
}
