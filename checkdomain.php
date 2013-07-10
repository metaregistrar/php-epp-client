<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
include_once('Protocols/EPP/eppRequests/eppIncludes.php');
include_once('Protocols/EPP/eppResponses/eppIncludes.php');
include_once('Protocols/EPP/eppData/eppIncludes.php');
// Connection object to Metaregistrar EPP server - this contains your userid and passwords!
include_once('Registries/Metaregistrar/metaregEppConnection.php');
include_once('Registries/IIS/iisEppConnection.php');
include_once('Registries/SIDN/sidnEppConnection.php');

// Base EPP commands: hello, login and logout
include_once('base.php');

/*
 * This script checks for the availability of domain names
 *
 * You can specify multiple domain names to be checked
 */


if ($argc <= 1)
{
    echo "Usage: checkdomain.php <domainnames>\n";
	echo "Please enter one or more domain names to check\n\n";
	die();
}

for ($i=1; $i<$argc; $i++)
{
    $domains[] = $argv[$i];
}

echo "Checking ".count($domains)." domain names\n";
try
{
    $conn = new sidnEppConnection(true);

    // Connect to the EPP server
    if ($conn->connect())
    {
        echo 'connected';
        if (login($conn))
        {
            echo 'loggedin';
            checkdomains($conn, $domains);
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



function checkdomains($conn, $domains)
{
	try
	{
		$check = new eppCheckRequest($domains);
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
			$checks = $response->getCheckedDomains();

			foreach ($checks as $check)
			{
                echo $check['domainname']." is ".($check['available'] ? 'free' : 'taken')." (".$check['reason'].")\n";
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