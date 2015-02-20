<?php
require('../autoloader.php');

/*
 * This script checks for the availability of domain names
 *
 * You can specify multiple domain names to be checked
 */


if ($argc <= 1) {
    echo "Usage: infodomain.php <domainname>\n";
    echo "Please enter a domain name retrieve\n\n";
    die();
}

$domainname = $argv[1];

echo "Retrieving info on " . $domainname . "\n";
try {
    $conn = new Metaregistrar\EPP\metaregEppConnection();

    // Connect to the EPP server
    if ($conn->connect()) {
        if (login($conn)) {
            infodomain($conn, $domainname);
            logout($conn);
        }
    } else {
        echo "ERROR CONNECTING\n";
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}


function infodomain($conn, $domainname) {
    try {
        $epp = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\eppInfoDomainRequest($epp);
        if ((($response = $conn->writeandread($info)) instanceof Metaregistrar\EPP\eppInfoDomainResponse) && ($response->Success())) {
            /* @var $response eppInfoDomainResponse */
            $d = $response->getDomain();
            echo "Info domain for " . $d->getDomainname() . ":\n";
            echo "Created on " . $response->getDomainCreateDate() . "\n";
            //echo "Last update on ".$response->getDomainUpdateDate()."\n";
            echo "Registrant " . $d->getRegistrant() . "\n";
            echo "Contact info:\n";
            foreach ($d->getContacts() as $contact) {
                echo "  " . $contact->getContactType() . ": " . $contact->getContactHandle() . "\n";
            }
            echo "Nameserver info:\n";
            foreach ($d->getHosts() as $nameserver) {
                echo "  " . $nameserver->getHostName() . "\n";
            }

        } else {
            echo "ERROR2\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo 'ERROR1';
        echo $e->getMessage() . "\n";
    }
}