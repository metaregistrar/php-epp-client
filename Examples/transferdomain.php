<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppTransferRequest;

/*
 * This script requests a domain name transfer into your account
 */

if ($argc <= 2) {
    echo "Usage: transferdomain.php <domainname> <authcode>\n";
    echo "Please the domain name and the auth code for transfer\n\n";
    die();
}
$domainname = $argv[1];
$authcode = $argv[2];

echo "Transferring $domainname\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect and login to the EPP server
        if ($conn->login()) {
            transferdomain($conn, $domainname, $authcode);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param eppConnection $conn
 * @param string $domainname
 * @param string $authcode
 */
function transferdomain($conn, $domainname, $authcode) {
    try {
        $domain = new eppDomain($domainname);
        $domain->setAuthorisationCode($authcode);
        $domain->setRegistrant(new \Metaregistrar\EPP\eppContactHandle('registrant handle'));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle('admin handle', \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle('tech handle', \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle('billing handle', \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING));
        $domain->addHost(new \Metaregistrar\EPP\eppHost('ns1.yourdomainprovider.net'));
        $domain->addHost(new \Metaregistrar\EPP\eppHost('ns2.yourdomainprovider.net'));
        $domain->addHost(new \Metaregistrar\EPP\eppHost('ns3.yourdomainprovider.net'));
        $transfer = new \Metaregistrar\EPP\metaregEppTransferExtendedRequest(eppTransferRequest::OPERATION_REQUEST,$domain);
        if ($response = $conn->request($transfer)) {
            /* @var $response Metaregistrar\EPP\eppTransferResponse */
            echo $response->getDomainName()," transfer request was succesful\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}