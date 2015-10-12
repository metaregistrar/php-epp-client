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

echo "Retrieving info on " . $domainname . "\n";
try {
    $conn = new Metaregistrar\EPP\metaregEppConnection();
    // Connect to the EPP server
    if ($conn->connect()) {
        if ($conn->login()) {
            foreach ($domains as $line) {
                list($domainname,$email,$name,$paymentid,$date)=explode(';',$line);
                $result = infodomain($conn, $domainname);
                echo $line.';'.$result."\n";
            }
            $conn->logout();
        }
    } else {
        echo "ERROR CONNECTING\n";
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $domainname string
 * @return string
 */
function infodomain($conn, $domainname) {
    try {
        $epp = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\eppInfoDomainRequest($epp);
        if ((($response = $conn->writeandread($info)) instanceof Metaregistrar\EPP\eppInfoDomainResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
            $d = $response->getDomain();
            echo "Info domain for " . $d->getDomainname() . ":\n";
            echo "Created on " . $response->getDomainCreateDate() . "\n";
            echo "Last update on ".$response->getDomainUpdateDate()."\n";
            echo "Registrant " . $d->getRegistrant() . "\n";
            echo "Contact info:\n";
            foreach ($d->getContacts() as $contact) {
                /* @var $contact Metaregistrar\EPP\eppContactHandle */
                echo "  " . $contact->getContactType() . ": " . $contact->getContactHandle() . "\n";
            }
            echo "Nameserver info:\n";
            foreach ($d->getHosts() as $nameserver) {
                /* @var $nameserver Metaregistrar\EPP\eppHost */
                echo "  " . $nameserver->getHostname() . "\n";
            }
        } else {
            echo "ERROR2\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        return $e->getMessage();
    }
    return null;
}