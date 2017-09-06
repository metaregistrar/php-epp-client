<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppSecdns;
use Metaregistrar\EPP\eppDnssecUpdateDomainRequest;
use Metaregistrar\EPP\eppException;

try {
    $domainname = 'dnssectest.nl';
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->enableDnssec();
        if ($conn->login()) {
            $add = new eppDomain($domainname);
            $sec = new eppSecdns();
            $sec->setKey('256', '8', 'AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9');
            $add->addSecdns($sec);
            $update = new eppDnssecUpdateDomainRequest($domainname, $add);
            if ($response = $conn->request($update)) {
                /* @var $response Metaregistrar\EPP\eppUpdateDomainResponse */
                echo "DNSSEC updated\n";
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

