<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
// Connection object to Metaregistrar EPP server - this contains your userid and passwords!
include_once('Registries/Metaregistrar/metaregEppConnection.php');
include_once('Registries/IIS/iisEppConnection.php');
include_once('Registries/SIDN/sidnEppConnection.php');
include_once('Registries/Donuts/donutsEppConnection.php');
include_once('Registries/DNSBE/dnsbeEppConnection.php');
include_once('Registries/FRL/frlEppConnection.php');

// Base EPP commands: hello, login and logout
include_once('base.php');

/*
 * This script checks for the availability of domain names in a certain launchphase
 *
 * You can specify multiple domain names to be checked
 */


if ($argc <= 1)
{
    echo "Usage: checklaunchdomain.php <domainnames>\n";
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
    $conn = new frlEppConnection(true);
    $conn->enableLaunchphase('landrush');
    // Connect to the EPP server
    if ($conn->connect())
    {
        if (login($conn))
        {
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
        $check = new eppLaunchCheckRequest($domains,$conn->getLaunchphase());
        if ((($response = $conn->writeandread($check)) instanceof eppLaunchCheckResponse) && ($response->Success()))
        {
            $phase = $response->getLaunchPhase();
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
