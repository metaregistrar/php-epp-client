<?php

require('../autoloader.php');

/*
 * This script checks for the availability of domain names in a certain launchphase
 *
 * You can specify multiple domain names to be checked
 */

if ($argc <= 1) {
    echo "Usage: checktmchdomain.php <domainnames>\n";
    echo "Please enter one or more domain names to check\n\n";
    die();
}

for ($i=1; $i<$argc; $i++) {
    $domains[] = iconv('ISO-8859-1','UTF-8',$argv[$i]);
}
echo "Checking ".count($domains)." domain names\n";
try {
    $conn = new Metaregistrar\EPP\frlEppConnection(false);
    $conn->enableLaunchphase('claims');
    // Connect to the EPP server
    if ($conn->connect()) {
        if (login($conn)) {
            checkdomains($conn, $domains);
            logout($conn);
        }
    }
    else {
        echo "ERROR CONNECTING\n";
    }
}
catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: ".$e->getMessage()."\n\n";
}



function checkdomains($conn, $domains) {
    try {
        $check = new Metaregistrar\EPP\eppLaunchCheckRequest($domains);
        $check->setLaunchPhase(Metaregistrar\EPP\eppLaunchCheckRequest::PHASE_CLAIMS,'test',Metaregistrar\EPP\eppLaunchCheckRequest::TYPE_CLAIMS);
        if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppLaunchCheckResponse) && ($response->Success())) {
            //$phase = $response->getLaunchPhase();
            $checks = $response->getDomainClaims();
            foreach ($checks as $check) {
                echo $check['domainname']." has ".($check['claimed'] ? 'a claim' : 'no claim')."\n";
                if ($check['claimed']) {
                    if ($check['claim']) {
                        if ($check['claim'] instanceof Metaregistrar\EPP\eppDomainClaim) {
                            echo "Claim validator: ".$check['claim']->getValidator().", claim key: ".$check['claim']->getClaimKey()."\n";
                            $tmch = new tmchEppConnection();
                            $output = $tmch->getCnis($check['claim']->getClaimKey());
                            var_dump($output);
                        }
                        else {
                            throw new eppException("Domain name ".$check['domainname']." is claimed, but no valid claim key is present");
                        }

                    }
                    else {
                        throw new eppException("Domain name ".$check['domainname']." is claimed, but no claim key is present");
                    }

                }
            }
        }
        else {
            echo "ERROR2\n";
        }
    }
    catch (Metaregistrar\EPP\eppException $e) {
        echo 'ERROR1: '.$e->getMessage()."\n";
    }
}
