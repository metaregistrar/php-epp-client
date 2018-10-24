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
$domainname  = $argv[1];
$authcode    = $argv[2];
$handle_reg  = '';
$handle_admin= '';
$handle_tech = '';
$handle_bill = '';

echo "Transferring $domainname\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect and login to the EPP server
        if ($conn->login()) {
            transferdomain($conn, $domainname, $authcode, $handle_reg, $handle_admin, $handle_tech, $handle_bill);
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
function transferdomain($conn, $domainname, $authcode, $handle_reg='', $handle_admin='', $handle_tech='', $handle_bill='', $nameservers= array() ) {

	if (empty($handle_reg)   && !empty($conn->settings['handle_reg'])  ) { $handle_reg   = $conn->settings['handle_reg'];  }
	if (empty($handle_admin) && !empty($conn->settings['handle_admin'])) { $handle_admin = $conn->settings['handle_admin'];}
	if (empty($handle_tech)  && !empty($conn->settings['handle_tech']) ) { $handle_tech  = $conn->settings['handle_tech']; }
	if (empty($handle_bill)  && !empty($conn->settings['handle_bill']) ) { $handle_bill  = $conn->settings['handle_bill']; }
	if (empty($nameservers)  && !empty($conn->settings['nameserver'])  ) { $nameservers  = $conn->settings['nameserver']; }
	
	if (empty($handle_reg)  ) { echo "ERROR: no handle_reg!  \n\n";$conn->logout();exit; }
	if (empty($handle_admin)) { echo "ERROR: no handle_admin!\n\n";$conn->logout();exit; }
	if (empty($handle_tech) ) { echo "ERROR: no handle_tech! \n\n";$conn->logout();exit; }
	if (empty($handle_bill) ) { echo "ERROR: no handle_bill! \n\n";$conn->logout();exit; }

    try {
        $domain = new eppDomain($domainname);
        $domain->setAuthorisationCode($authcode);
        $domain->setRegistrant(new \Metaregistrar\EPP\eppContactHandle($handle_reg));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($handle_admin, \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($handle_tech,  \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($handle_bill,  \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING));
		foreach($nameservers as $nameserver) {
            $domain->addHost(new \Metaregistrar\EPP\eppHost($nameserver));
        }
        $transfer = new \Metaregistrar\EPP\metaregEppTransferExtendedRequest(eppTransferRequest::OPERATION_REQUEST,$domain);
        if ($response = $conn->request($transfer)) {
            /* @var $response Metaregistrar\EPP\eppTransferResponse */
            echo $response->getDomainName()," transfer request was succesful\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}
