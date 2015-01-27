<?php
require('../autoloader.php');

/*
 * This script checks for the availability of domain names
 * You can specify multiple domain names to be checked
 */


if ($argc <= 1)
{
    echo "Usage: checkdomain.php <domainnames>\n";
	echo "Please enter one or more domain names to check\n\n";
	die();
}

for ($i=1; $i<$argc; $i++)
{
    $domains[] = $argv[$i];
}

echo "Checking ".count($domains)." domain names\n";
try
{
    $conn = new Metaregistrar\EPP\metaregEppConnection();

    // Connect to the EPP server
    if ($conn->connect()) {
        if (login($conn)) {
            checkdomains($conn, $domains);
            logout($conn);
        }
    }
    else {
        echo "ERROR CONNECTING\n";
    }
}
catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: ".$e->getMessage()."\n\n";
}



function checkdomains($conn, $domains) {
	try {
		$check = new Metaregistrar\EPP\eppCheckRequest($domains);
		if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppCheckResponse) && ($response->Success())) {
			$checks = $response->getCheckedDomains();

			foreach ($checks as $check) {
                echo $check['domainname']." is ".($check['available'] ? 'free' : 'taken')." (".$check['reason'].")\n";
			}
		}
        else {
            echo "ERROR2\n";
        }
	}
	catch (Metaregistrar\EPP\eppException $e) {
        echo 'ERROR1';
		echo $e->getMessage()."\n";
	}
}