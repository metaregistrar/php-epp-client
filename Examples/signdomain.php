<?php
require('../autoloader.php');


try {
    $domainname = 'dnssectest.nl';
    $conn = new Metaregistrar\EPP\sidnEppConnection();
    eppConnect($conn);
    $add = new Metaregistrar\EPP\eppDomain($domainname);
    $sec = new Metaregistrar\EPP\eppSecdns();
    $sec->setKey('256', '8', 'AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9');
    $add->addSecdns($sec);
    $domain = new Metaregistrar\EPP\eppDnssecUpdateDomainRequest($domainname, $add);
    if ((($response = $conn->writeandread($domain)) instanceof Metaregistrar\EPP\eppUpdateResponse) && ($response->Success())) {
        echo "OKAY\n";
    }
    $this->eppDisconnect($conn);
    return true;
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    eppDisconnect($conn);
}


function eppConnect($conn) {
    if ($conn->connect()) {
        #
        # Send greeting to EPP server
        #
        $login = new Metaregistrar\EPP\eppLoginRequest();
        if ((($response = $conn->writeandread($login)) instanceof Metaregistrar\EPP\eppLoginResponse) && ($response->Success())) {
        } else {
            throw new Metaregistrar\EPP\eppException('Unable to login to EPP');
        }
    } else {
        throw new Metaregistrar\EPP\eppException('Unable to connect to EPP');
    }
}


function eppDisconnect($conn) {
    $logout = new Metaregistrar\EPP\eppLogoutRequest();
    $conn->writeandread($logout);
    $conn->disconnect();
}
