<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
// Connection object to Metaregistrar EPP server - this contains your userid and passwords!
include_once('Registries/FRL/frlEppConnection.php');
// Base EPP commands: hello, login and logout
include_once('base.php');

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
$conn = new frlEppConnection();
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
        $contactinfo = new eppContactHandle($contactid);
        $check = new eppCheckRequest($contactinfo);
        if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
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
        $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContactPostalInfo::POSTAL_TYPE_INTERNATIONAL);
        $contactinfo = new eppContact($postalinfo, $email, $telephone);
        $contact = new eppCreateContactRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof eppCreateResponse) && ($response->Success()))
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
        $domain = new eppDomain($domainname, $registrant);
        $reg = new eppContactHandle($registrant);
        $domain->setRegistrant($reg);
        $admin = new eppContactHandle($admincontact,eppContactHandle::CONTACT_TYPE_ADMIN);
        $domain->addContact($admin);
        $tech = new eppContactHandle($techcontact,eppContactHandle::CONTACT_TYPE_TECH);
        $domain->addContact($tech);
        $billing = new eppContactHandle($billingcontact,eppContactHandle::CONTACT_TYPE_BILLING);
        $domain->addContact($billing);
        $domain->setAuthorisationCode('rand0m');
        if (is_array($nameservers))
        {
            foreach ($nameservers as $nameserver)
            {
                $host = new eppHost($nameserver);
                $domain->addHost($host);
            }
        }
        $create = new eppLaunchCreateDomainRequest($domain);
        $create->setLaunchPhase('claims','application');
        if ((($response = $conn->writeandread($create)) instanceof eppLaunchCreateDomainResponse) && ($response->Success()))
        {
            /* @var eppLaunchCreateResponse $response */
            echo "Domain ".$response->getDomainName()." created on ".$response->getDomainCreateDate().", expiration date is ".$response->getDomainExpirationDate()."\n";
            echo "Registration phase: ".$response->getLaunchPhase()." and Application ID: ".$response->getLaunchApplicationID()."\n";
        }
    }
    catch (eppException $e)
    {
        echo $e->getMessage()."\n";
    }
}