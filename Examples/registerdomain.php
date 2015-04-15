<?php
require('../autoloader.php');

/*
 * This sample script registers a domain name within your account
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */


if ($argc <= 1)
{
    echo "Usage: registerdomain.php <domainname>\n";
	echo "Please enter the domain name to be created\n\n";
	die();
}

$domainname = $argv[1];

echo "Registering $domainname\n";
$conn = new Metaregistrar\EPP\metaregEppConnection();
// Connect to the EPP server
if ($conn->connect()) {
    if (login($conn)) {
        if (!checkhosts($conn, array('ns1.metaregistrar.nl'))) {
            createhost($conn, 'ns1.metaregistrar.nl');
        }
        if (!checkhosts($conn, array('ns5.metaregistrar.nl'))) {
            createhost($conn, 'ns2.metaregistrar.nl');
        }
        $nameservers = array('ns1.metaregistrar.nl','ns2.metaregistrar.nl');
        $contactid = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
        if ($contactid) {
            createdomain($conn, $domainname, $contactid, $contactid, $contactid, $contactid, $nameservers);
        }
        logout($conn);
    }
}


function checkcontact($conn, $contactid) {
    /* @var $conn Metaregistrar\EPP\eppConnection */
    try {
        $contactinfo = new Metaregistrar\EPP\eppContactHandle($contactid);
        $check = new Metaregistrar\EPP\eppCheckRequest($contactinfo);
        if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppCheckResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCheckResponse */
            $checks = $response->getCheckedContacts();
            foreach ($checks as $contact => $check) {
                echo "Contact $contact " . ($check ? 'does not exist' : 'exists') . "\n";
            }
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    /* @var $conn Metaregistrar\EPP\eppConnection */
    try {
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, Metaregistrar\EPP\eppContactPostalInfo::POSTAL_TYPE_LOCAL);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contactinfo->setPassword('fubar');
        $contact = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
            return $response->getContactId();
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}


function checkhosts($conn, $hosts) {
    /* @var $conn Metaregistrar\EPP\eppConnection */
    try {
        $checkhost = array();
        foreach ($hosts as $host) {
            $checkhost[] = new Metaregistrar\EPP\eppHost($host);
        }
        $check = new Metaregistrar\EPP\eppCheckRequest($checkhost);
        if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppCheckResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCheckResponse */
            $checks = $response->getCheckedHosts();
            $allchecksok = true;
            foreach ($checks as $hostname => $check) {
                echo "$hostname " . ($check ? 'does not exist' : 'exists') . "\n";
                if ($check) {
                    $allchecksok = false;
                }
            }
            return $allchecksok;
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}


function createhost($conn, $hostname, $ipaddress=null) {
    /* @var $conn Metaregistrar\EPP\eppConnection */
    try {
        $create = new Metaregistrar\EPP\eppHost($hostname,$ipaddress);
        $host = new Metaregistrar\EPP\eppCreateHostRequest($create);
        if ((($response = $conn->writeandread($host)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Host created on " . $response->getHostCreateDate() . " with name " . $response->getHostName() . "\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


function createdomain($conn, $domainname, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    /* @var $conn Metaregistrar\EPP\eppConnection */
    try {
        $domain = new Metaregistrar\EPP\eppDomain($domainname, $registrant);
        $reg = new Metaregistrar\EPP\eppContactHandle($registrant);
        $domain->setRegistrant($reg);
        $admin = new Metaregistrar\EPP\eppContactHandle($admincontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN);
        $domain->addContact($admin);
        $tech = new Metaregistrar\EPP\eppContactHandle($techcontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH);
        $domain->addContact($tech);
        $billing = new Metaregistrar\EPP\eppContactHandle($billingcontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING);
        $domain->addContact($billing);
        $domain->setAuthorisationCode('rand0m');
        if (is_array($nameservers))
        {
            foreach ($nameservers as $nameserver)
            {
                $host = new Metaregistrar\EPP\eppHost($nameserver);
                $domain->addHost($host);
            }
        }
        $create = new Metaregistrar\EPP\eppCreateDomainRequest($domain, true);
        if ((($response = $conn->writeandread($create)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}