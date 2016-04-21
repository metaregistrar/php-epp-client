<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;

/*
 * This script uses the login epp command to perform a password change on EPP
 */


if ($argc <= 1) {
    echo "Usage: changepassword <password>\n";
    echo "Please enter new password you want to use\n\n";
    die();
}

$newpassword = $argv[1];

echo "Changing password\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->setNewPassword($newpassword);
        // Connect and login to the EPP server
        if ($conn->login()) {
            echo "Password was changed, you are logged-out automatically\n";
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}
