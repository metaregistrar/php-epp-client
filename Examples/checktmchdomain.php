<?php

require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppLaunchCheckRequest;
use Metaregistrar\EPP\eppDomainClaim;
use Metaregistrar\TMCH\cnisTmchConnection;
use Metaregistrar\TMCH\tmchException;

/*
 * This script checks for the availability of domain names in the claims phase
 * You can specify multiple domain names to be checked
 */

if ($argc <= 1) {
    echo "Usage: checktmchdomain.php <domainnames>\n";
    echo "Please enter one or more domain names to check\n\n";
    die();
}

for ($i = 1; $i < $argc; $i++) {
    $domains[] = iconv('ISO-8859-1', 'UTF-8', $argv[$i]);
}
echo "Checking " . count($domains) . " domain names\n";
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->enableLaunchphase('claims');
        // Connect to the EPP server
        if ($conn->login()) {
            checkdomains($conn, $domains);
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param $conn eppConnection
 * @param $domains array
 */
function checkdomains($conn, $domains) {
    try {
        $check = new eppLaunchCheckRequest($domains);
        $check->setLaunchPhase(eppLaunchCheckRequest::PHASE_CLAIMS, 'test', eppLaunchCheckRequest::TYPE_CLAIMS);
        if ($response = $conn->request($check)) {
            /* @var $response Metaregistrar\EPP\eppLaunchCheckResponse */
            //$phase = $response->getLaunchPhase();
            $checks = $response->getDomainClaims();

            foreach ($checks as $check) {
                echo $check['domainname'] . " has " . ($check['claimed'] ? 'a claim' : 'no claim') . "\n";
                if ($check['claimed']) {
                    if ($check['claim']) {
                        if ($check['claim'] instanceof eppDomainClaim) {
                            echo "Claim validator: " . $check['claim']->getValidator() . ", claim key: " . $check['claim']->getClaimKey() . "\n";
                            // Do not forget to fill in the CNIS login details!
                            $tmch = new cnisTmchConnection('');
                            $output = $tmch->getCnis($check['claim']->getClaimKey());
                            echo "Notice ID: ".$output->getNoticeId()." Not after: ".$output->getNotAfter()."\n";
                        } else {
                            throw new Metaregistrar\EPP\eppException("Domain name " . $check['domainname'] . " is claimed, but no valid claim key is present");
                        }

                    } else {
                        throw new Metaregistrar\EPP\eppException("Domain name " . $check['domainname'] . " is claimed, but no claim key is present");
                    }

                }
            }
        } else {
            echo "ERROR2\n";
        }
    } catch (eppException $e) {
        echo 'ERROR1: ' . $e->getMessage() . "\n";
    } catch (tmchException $t) {
        echo 'ERROR TMCH: ' . $t->getMessage() . "\n";
    }

}
