<?php

try
{
    $conn = new sidnEppConnection();
    $this->eppConnect($conn);
    $secadd = new eppSecdns();
    $secadd->setKey('256','8','AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9');
    $domain = new eppDnssecUpdateDomainRequest('dnssectransfer.nl',$secadd);
    if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
    {
        echo "OKAY\n";
    }
    $this->eppDisconnect($conn);
    return true;
}
catch (eppException $e)
{
    $this->eppDisconnect($conn);
}


function eppConnect()
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
