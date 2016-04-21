<?php

require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\eppLaunchCreateDomainRequest;


/*
 * This sample script registers a domain name within your account for a specific launch phase
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */


if ($argc <= 1) {
    echo "Usage: createlaunchdomain.php <domainname>\n";
    echo "Please enter the domain name to be created\n\n";
    die();
}

$domainname = $argv[1];

echo "Registering $domainname\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            $contactid = 'mrg54b6560e01ddf';
            $techcontact = $contactid;
            $billingcontact = $contactid;
            if ($contactid) {
                createdomain($conn, $domainname, $contactid, $contactid, $techcontact, $billingcontact, array('ns1.metaregistrar.nl', 'ns2.metaregistrar.nl'));
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
        echo "ERROR: " . $e->getMessage() . "\n\n";
}


/**
 * @param $conn eppConnection
 * @param $domainname string
 * @param $registrant string
 * @param $admincontact string
 * @param $techcontact string
 * @param $billingcontact string
 * @param $nameservers array
 * @return bool
 */
function createdomain($conn, $domainname, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    $domain = new eppDomain($domainname, $registrant);
    $domain->setRegistrant(new eppContactHandle($registrant));
    $domain->addContact(new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN));
    $domain->addContact(new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH));
    $domain->addContact(new eppContactHandle($billingcontact, eppContactHandle::CONTACT_TYPE_BILLING));
    $domain->setAuthorisationCode($domain->generateRandomString(12));
    if (is_array($nameservers)) {
        foreach ($nameservers as $nameserver) {
            $domain->addHost(new eppHost($nameserver));
        }
    }
    $create = new eppLaunchCreateDomainRequest($domain);
    $create->setLaunchPhase('claims', 'application');
    if ($response = $conn->request($create)) {
        /* @var Metaregistrar\EPP\eppLaunchCreateDomainResponse $response */
        echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        echo "Registration phase: " . $response->getLaunchPhase() . " and Application ID: " . $response->getLaunchApplicationID() . "\n";
    }
    return null;
}
