<?php

if ($argc <= 1)
{
    echo "Usage: signdomain.php <domainname>\n";
    echo "Please enter the domain name to be modified\n\n";
    die();
}

$domainname = $argv[1];

// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
include_once('Registries/SIDN/sidnEppConnection.php');
include_once('base.php');


try
{
    $conn = new sidnEppConnection();
    eppConnect($conn);
    $secadd = new eppSecdns();
    $secadd->setKey('257','8','AwEAAePkXB7zXIdNr2NTYjh/jiklP327EuHS2Gi0h/k00HdiCqJvP8hqQAuM3WFiop8jNuLp9s0ywDYIreY2X/Q/zZRJi6zoIrdcnH8hiNxD2RFkiGpjDsY/F3juDQZChZuhQmYcY0XSkxb7ZSeGA9790rEE33H0zXxbzpQSwWnBWIAumPvl+eLenCFhK+2NAvBsqH2B1Oeit5owrB4Xvnta6gDoj/tjb5n21nFSReaEyhTXGBd+O8SSAxuBN7waNq9uZuVF2sWK4JSLbjVwXuVByzCB+rrnvd6QUlqExA8olzgwZgEZltMj1mWkAdi7YFtfBfGSSmHz3lblQj+k6bW6EOk=');
    $domain = new eppDnssecUpdateDomainRequest($domainname,$secadd);
    if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
    {
        echo $response->getResultMessage()."\n";
        echo "OKAY\n";
    }
    eppDisconnect($conn);
    return true;
}
catch (eppException $e)
{
    echo $e->getMessage()."\n";
    if ($response instanceof eppUpdateResponse)
    {
        echo $response->textContent."\n";
    }
    eppDisconnect($conn);
}


function eppConnect($conn)
{
    if ($conn->connect())
    {
        #
        # Send greeting to EPP server
        #
        $login = new eppLoginRequest();
        if ((($response = $conn->writeandread($login)) instanceof eppLoginResponse) && ($response->Success()))
        {
        }
        else
        {
            throw new eppException('Unable to login to EPP');
        }
    }
    else
    {
        throw new eppException('Unable to connect to EPP');
    }
}


function eppDisconnect($conn)
{
    $logout = new eppLogoutRequest();
    $conn->writeandread($logout);
    $conn->disconnect();
}
