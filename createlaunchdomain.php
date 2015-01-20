<?php

require('./autoloader.php');


/*
 * This sample script registers a domain name within your account
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */


if ($argc <= 1)
{
    echo "Usage: createlaunchdomain.php <domainname>\n";
    echo "Please enter the domain name to be created\n\n";
    die();
}

$domainname = $argv[1];

echo "Registering $domainname\n";
$conn = new Metaregistrar\EPP\frlEppConnection();
// Connect to the EPP server
if ($conn->connect())
{
    if (login($conn))
    {
        #$contactid = createcontact($conn,'test@test.com','061234567890','Person name',null,'Address 1','12345','City','NL');
        $contactid = 'mrg54b6560e01ddf';
        $techcontact = $contactid;
        $billingcontact = $contactid;
        if ($contactid)
        {
            createdomain($conn,$domainname,$contactid,$contactid,$techcontact,$billingcontact,array('ns1.metaregistrar.nl','ns2.metaregistrar.nl'));
        }
        logout($conn);
    }
}


function checkcontact($conn, $contactid)
{
    try
    {
        $contactinfo = new Metaregistrar\EPP\eppContactHandle($contactid);
        $check = new Metaregistrar\EPP\eppCheckRequest($contactinfo);
        if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppCheckResponse) && ($response->Success()))
        {
            $checks = $response->getCheckedContacts();
            foreach ($checks as $contact => $check)
            {
                echo "Contact $contact ".($check ? 'does not exist' : 'exists')."\n";
            }
        }
    }
    catch (eppException $e)
    {
        echo $e->getMessage()."\n";
    }
}


function createcontact($conn,$email,$telephone,$name,$organization,$address,$postcode,$city, $country)
{
    try
    {
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContactPostalInfo::POSTAL_TYPE_INTERNATIONAL);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contact = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success()))
        {
            echo "Contact created on ".$response->getContactCreateDate()." with id ".$response->getContactId()."\n";
            return $response->getContactId();
        }
    }
    catch (eppException $e)
    {
        echo $e->getMessage()."\n";
    }
    return null;
}

function createdomain($conn,$domainname,$registrant,$admincontact,$techcontact,$billingcontact,$nameservers)
{
    try
    {
        $domain = new Metaregistrar\EPP\eppDomain($domainname, $registrant);
        $reg = new Metaregistrar\EPP\eppContactHandle($registrant);
        $domain->setRegistrant($reg);
        $admin = new Metaregistrar\EPP\eppContactHandle($admincontact,Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN);
        $domain->addContact($admin);
        $tech = new Metaregistrar\EPP\eppContactHandle($techcontact,Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH);
        $domain->addContact($tech);
        $billing = new Metaregistrar\EPP\eppContactHandle($billingcontact,Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING);
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
        $create = new Metaregistrar\EPP\eppLaunchCreateDomainRequest($domain);
        $create->setLaunchPhase('claims','application');
        if ((($response = $conn->writeandread($create)) instanceof Metaregistrar\EPP\eppLaunchCreateDomainResponse) && ($response->Success()))
        {
            /* @var Metaregistrar\EPP\eppLaunchCreateResponse $response */
            echo "Domain ".$response->getDomainName()." created on ".$response->getDomainCreateDate().", expiration date is ".$response->getDomainExpirationDate()."\n";
            echo "Registration phase: ".$response->getLaunchPhase()." and Application ID: ".$response->getLaunchApplicationID()."\n";
        }
    }
    catch (eppException $e)
    {
        echo $e->getMessage()."\n";
    }
}