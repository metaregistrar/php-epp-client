<?php
require('../autoloader.php');

/*
 * This sample script registers a domain name within your account
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */


//if ($argc <= 1)
//{
//    echo "Usage: registerdomain.php <domainname>\n";
///	echo "Please enter the domain name to be created\n\n";
//	die();
//}

//$domainname = $argv[1];


$domainname = 'waterontharder.frl';
echo "Registering $domainname\n";
$conn = new Metaregistrar\EPP\metaregEppConnection();
// Connect to the EPP server
if ($conn->connect())
{
    if (login($conn))
    {
        if (!checkhosts($conn, array('ns1.metaregistrar.nl')))
        {
            createhost($conn,'ns1.metaregistrar.nl');
        }
        if (!checkhosts($conn, array('ns2.metaregistrar.nl')))
        {
            createhost($conn,'ns2.metaregistrar.nl');
        }
        #if (!checkhosts($conn, array('ns3.metaregistrar.com')))
        #{
        #    createhost($conn,'ns3.metaregistrar.com');
        #}
        #$contactid = createcontact($conn,'test@test.com','061234567890','Person name',null,'Address 1','12345','City','NL');
        #if ($contactid)
        #{
        createdomain($conn,$domainname,'xwr-npgal4','xwr-npgal4','xwr-npgal4','xwr-npgal4',null);
        #}
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
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContactPostalInfo::POSTAL_TYPE_LOCAL);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contact = new Metaregistrar\EPP\iisEppCreateRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success()))
        {
            echo "Contact created on ".$response->getContactCreateDate()." with id ".$response->getContactId()."\n";
            return $response->getContactId();
        }
	}
	catch (Metaregistrar\EPP\eppException $e)
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
            $checkhost[] = new Metaregistrar\EPP\eppHost($host);
        }
		$check = new Metaregistrar\EPP\eppCheckRequest($checkhost);
		if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppCheckResponse) && ($response->Success()))
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
        $create = new Metaregistrar\EPP\eppHost($hostname);
		$host = new Metaregistrar\EPP\eppCreateRequest($create);
		if ((($response = $conn->writeandread($host)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success()))
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
        #if (is_array($nameservers))
        #{
        #    foreach ($nameservers as $nameserver)
        #    {
        #        $host = new eppHost($nameserver);
        #        $domain->addHost($host);
        #    }
        #}
        $create = new Metaregistrar\EPP\eppCreateDomainRequest($domain);
        $create->setForcehostattr(true);
        if ((($response = $conn->writeandread($create)) instanceof Metaregistrar\EPP\eppCreateDomainResponse) && ($response->Success()))
        {
            echo "Domain ".$response->getDomainName()." created on ".$response->getDomainCreateDate().", expiration date is ".$response->getDomainExpirationDate()."\n";
        }
    }
    catch (Metaregistrar\EPP\eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}