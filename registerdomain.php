<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
include_once('Protocols/EPP/eppRequests/eppIncludes.php');
include_once('Protocols/EPP/eppResponses/eppIncludes.php');
include_once('Protocols/EPP/eppData/eppIncludes.php');
// Connection object to Metaregistrar EPP server - this contains your userid and passwords!
include_once('Registries/Metaregistrar/metaregEppConnection.php');
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
    echo "Usage: createdomain.php <domainname>\n";
	echo "Please enter the domain name to be created\n\n";
	die();
}

$domainname = $argv[1];

echo "Registering $domainname\n";
$conn = new metaregEppConnection();
// Connect to the EPP server
if ($conn->connect())
{
	if (greet($conn))
	{
		if (login($conn))
		{
            if (!checkhosts($conn, array('ns1.metaregistrar.com')))
            {
                createhost($conn,'ns1.metaregistrar.com');
            }
            if (!checkhosts($conn, array('ns2.metaregistrar.com')))
            {
                createhost($conn,'ns2.metaregistrar.com');
            }
            if (!checkhosts($conn, array('ns3.metaregistrar.com')))
            {
                createhost($conn,'ns3.metaregistrar.com');
            }
            $contactid = createcontact($conn,'test@test.com','061234567890','Person name','Company','Address 1','12345','City','NL');
            if ($contactid)
            {
                createdomain($conn,$domainname,$contactid,$contactid,$contactid,$contactid,array('ns1.metaregistrar.com','ns2.metaregistrar.com','ns3.metaregistrar.com'));
            }
            logout($conn);
        }
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
        $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode);
        $contactinfo = new eppContact($postalinfo, $email, $telephone);
        $contact = new eppCreateRequest($contactinfo);
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



function checkhosts($conn, $hosts)
{
	try
	{
        foreach ($hosts as $host)
        {
            $checkhost[] = new eppHost($host);
        }
		$check = new eppCheckRequest($checkhost);
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
			$checks = $response->getCheckedHosts();
            $allchecksok = true;
			foreach ($checks as $hostname => $check)
			{
				echo "$hostname ".($check ? 'does not exist' : 'exists')."\n";
                if ($check)
                {
                    $allchecksok = false;
                }
			}
            return $allchecksok;
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}


function createhost($conn, $hostname)
{
	try
	{
        $create = new eppHost($hostname);
		$host = new eppCreateRequest($create);
		if ((($response = $conn->writeandread($host)) instanceof eppCreateResponse) && ($response->Success()))
		{
			echo "Host created on ".$response->getHostCreateDate()." with name ".$response->getHostName()."\n";
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}


function createdomain($conn,$domainname,$registrant,$admincontact,$techcontact,$billingcontact,$nameservers)
{
    try
    {
        $domain = new eppDomain($domainname);
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
        $create = new eppCreateRequest($domain);
        if ((($response = $conn->writeandread($create)) instanceof eppCreateResponse) && ($response->Success()))
        {
            echo "Domain ".$response->getDomainName()." created on ".$response->getDomainCreateDate().", expiration date is ".$response->getDomainExpirationDate()."\n";
        }
    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}