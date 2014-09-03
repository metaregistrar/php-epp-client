<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
include_once('Protocols/EPP/eppRequests/eppIncludes.php');
include_once('Protocols/EPP/eppResponses/eppIncludes.php');
include_once('Protocols/EPP/eppData/eppIncludes.php');
// Connection objects to registry servers - this contains your userids and passwords!
include_once('Registries/Metaregistrar/metaregEppConnection.php');
include_once('Registries/IIS/iisEppConnection.php');
include_once('Registries/SIDN/sidnEppConnection.php');
include_once('Registries/EURID/euridEppConnection.php');

// Base EPP commands: hello, login and logout
include_once('base.php');

/*
 * This script checks for the availability of domain names
 *
 * You can specify multiple domain names to be checked
 */


if ($argc <= 1)
{
    echo "Usage: infodomain.php <domainname>\n";
	echo "Please enter a domain name retrieve\n\n";
	die();
}

$domainname = $argv[1];

echo "Retrieving info on ".$domainname."\n";
try
{
    $conn = new euridEppConnection(true);

    // Connect to the EPP server
    if ($conn->connect())
    {
        if (login($conn))
        {
            infodomain($conn, $domainname);
            logout($conn);
        }
    }
    else
    {
        echo "ERROR CONNECTING\n";
    }
}
catch (eppException $e)
{
    echo "ERROR: ".$e->getMessage()."\n\n";
}



function infodomain($conn, $domainname)
{
	try
	{
        $epp = new eppDomain($domainname);
		$info = new euridEppInfoDomainRequest($epp);
		if ((($response = $conn->writeandread($info)) instanceof eppInfoDomainResponse) && ($response->Success()))
		{
            /* @var $response eppInfoDomainResponse */
            $d = $response->getDomain();
            echo "Info domain for ".$d->getDomainname().":\n";
            echo "Created on ".$response->getDomainCreateDate()."\n";
            echo "Last update on ".$response->getDomainUpdateDate()."\n";
            echo "Registrant ".$d->getRegistrant()."\n";
            echo "Contact info:\n";
            foreach ($d->getContacts() as $contact)
            {
                echo "  ".$contact->getContactType().": ".$contact->getContactHandle()."\n";
            }
            echo "Nameserver info:\n";
            foreach ($d->getHosts() as $nameserver)
            {
                echo "  ".$nameserver->getHostName()."\n";
            }

		}
        else
        {
            echo "ERROR2\n";
        }
	}
	catch (eppException $e)
	{
        echo 'ERROR1';
		echo $e->getMessage()."\n";
	}
}