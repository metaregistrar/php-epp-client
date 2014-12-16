<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
// Connection object to EPP server - this contains your userid and passwords!
include_once('Registries/Metaregistrar/metaregEppConnection.php');
include_once('Registries/IIS/iisEppConnection.php');
include_once('Registries/SIDN/sidnEppConnection.php');
include_once('Registries/Donuts/donutsEppConnection.php');


try
{
    $domainname = 'dnssectest.nl';
    $conn = new sidnEppConnection();
    eppConnect($conn);
    $add = new eppDomain($domainname);
    $sec = new eppSecdns();
    $sec->setKey('256','8','AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9');
    $add->addSecdns($sec);
    $domain = new eppDnssecUpdateDomainRequest($domainname,$add);
    if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
    {
        echo "OKAY\n";
    }
    $this->eppDisconnect($conn);
    return true;
}
catch (eppException $e)
{
    echo "ERROR: ".$e->getMessage()."\n";
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
