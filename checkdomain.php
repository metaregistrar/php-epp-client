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
$conn = new metaregEppConnection();
// Connect to the EPP server
if ($conn->connect())
{
	if (greet($conn))
	{
		if (login($conn))
		{
            checkdomains($conn, $domains);
            logout($conn);
        }
    }
}



function checkdomains($conn, $domains)
{
	try
	{
		$check = new eppCheckRequest($domains);
        //echo $check->saveXML();
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
            //echo $response->saveXML();
			$checks = $response->getCheckedDomains();

			foreach ($checks as $check)
			{
                echo $check['domainname']." is ".($check['available'] ? 'free' : 'taken')." (".$check['reason'].")\n";
			}
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";

	}
}