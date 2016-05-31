<?php
require('../../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\ptEppInfoDomainRequest;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppHost;


/*
 * This script retrieves all information for a specific domain name
 */


if ($argc <= 1) {
    echo "Usage: infodomain.php <domainname>\n";
    echo "Please enter a domain name retrieve\n\n";
    die();
}
$domainname = $argv[1];

echo "Retrieving info on " . $domainname . "\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('pt.ini',true)) {
        // Connect to the EPP server
        if ($conn->login()) {
            $roid = '12345';
            $result = infodomain($conn, $domainname, $roid);
            $conn->logout();
        }
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $domainname string
 * @return string
 */
function infodomain($conn, $domainname,$roid) {
    try {
        $domain = new eppDomain($domainname);
        $domain->setAuthorisationCode(null);
        $info = new ptEppInfoDomainRequest($domain,$roid);
        if ($response = $conn->request($info)) {
            /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
            $d = $response->getDomain();
            echo "Info domain for " . $d->getDomainname() . ":\n";
            echo "Created on " . $response->getDomainCreateDate() . "\n";
            echo "Last update on ".$response->getDomainUpdateDate()."\n";
            echo "Registrant " . $d->getRegistrant() . "\n";
            echo "Contact info:\n";
            foreach ($d->getContacts() as $contact) {
                /* @var $contact eppContactHandle */
                echo "  " . $contact->getContactType() . ": " . $contact->getContactHandle() . "\n";
            }
            echo "Nameserver info:\n";
            foreach ($d->getHosts() as $nameserver) {
                /* @var $nameserver eppHost */
                echo "  " . $nameserver->getHostname() . "\n";
            }
        } else {
            echo "ERROR2\n";
        }
    } catch (eppException $e) {
        return $e->getMessage();
    }
    return null;
}