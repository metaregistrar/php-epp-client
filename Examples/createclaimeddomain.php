<?php

require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppLaunchCheckRequest;
use Metaregistrar\EPP\eppDomainClaim;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\eppCreateDomainRequest;
use Metaregistrar\EPP\eppLaunchCreateDomainRequest;
use Metaregistrar\TMCH\cnisTmchConnection;
use Metaregistrar\TMCH\tmchException;

/*
 * This sample script registers a domain name within your account in the claims phase
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */

$now = $current_date = gmdate("Y-m-d\TH:i:s\Z");
$claims = array(
    'test-claims-1.frl' => array('noticeid' => '2a87fdbb9223372036854775807', 'notafter' => '2019-09-04T07:47:03.123Z', 'lookup' => '2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX4R2127', 'confirmed' => $now),
    'test-claims-2.frl' => array('noticeid' => 'e434f0f59223372036854775807', 'notafter' => '2018-10-01T15:40:13.843Z', 'lookup' => '2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX4R2609', 'confirmed' => $now),
    'test-claims-3.frl' => array('noticeid' => '3d2f541d9223372036854775807', 'notafter' => '2018-11-06T08:17:08.8Z', 'lookup' => '2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX3R2333', 'confirmed' => $now),
    'a-b-c-d-e-fg.amsterdam' => array('noticeid' => '27d5501a0000000000000407286', 'notafter' => '2015-05-30T00:00:00.0Z', 'lookup' => '2015052800/9/6/9/lpexfNxa2c0WNTKtzWXsizak0000000408', 'confirmed' => $now),
    'a-b-c-d-ef-g.amsterdam' => array('noticeid' => 'f9378df20000000000000407484', 'notafter' => '2015-05-30T00:00:00.0Z', 'lookup' => '2015052800/8/F/1/jxDmzfePmZekFgnG9dI8F0zG0000000606', 'confirmed' => $now)

);

$domainname = '';
try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        $conn->enableLaunchphase('claims');
        // Connect and login to the EPP server
        if ($conn->login()) {
            $contactid = '';
            $techcontact = $contactid;
            $billingcontact = $contactid;
            $nameservers = array('ns1.metaregistrar.nl','ns2.metaregistrar.nl');
            echo "Registering $domainname\n";
            $claim = checkdomainclaim($conn,$domainname);
            if ($claim) {
                createclaimeddomain($conn, $domainname, $claim, $contactid, $contactid, $techcontact, $billingcontact, $nameservers);
            } else {
                createdomain($conn, $domainname, $contactid, $contactid, $techcontact, $billingcontact, $nameservers);

            }
            $conn->logout();
        }

    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param eppConnection $conn
 * @param string $domainname
 * @return array|null
 * @throws eppException
 * @throws tmchException
 */
function checkdomainclaim($conn, $domainname) {
    $check = new eppLaunchCheckRequest(array($domainname));
    $check->setLaunchPhase(eppLaunchCheckRequest::PHASE_CLAIMS, null, eppLaunchCheckRequest::TYPE_CLAIMS);
    if ($response = $conn->request($check)) {
        //$phase = $response->getLaunchPhase();
        /* @var Metaregistrar\EPP\eppLaunchCheckResponse $response */
        $checks = $response->getDomainClaims();

        foreach ($checks as $check) {
            echo $check['domainname'] . " has " . ($check['claimed'] ? 'a claim' : 'no claim') . "\n";
            if ($check['claimed']) {
                if ($check['claim']) {
                    if ($check['claim'] instanceof eppDomainClaim) {
                        echo "Claim validator: " . $check['claim']->getValidator() . ", claim key: " . $check['claim']->getClaimKey() . "\n";
                        $tmch = new cnisTmchConnection(true,'settingslive.ini');
                        $claim = array();
                        $output = $tmch->getCnis($check['claim']->getClaimKey());
                        /* @var $output Metaregistrar\TMCH\tmchClaimData */
                        $claim['noticeid']= $output->getNoticeId();
                        $claim['notafter']= $output->getNotAfter();
                        $claim['confirmed']= gmdate("Y-m-d\TH:i:s\Z");
                        return $claim;
                    } else {
                        throw new eppException("Domain name " . $check['domainname'] . " is claimed, but no valid claim key is present");
                    }

                } else {
                    throw new eppException("Domain name " . $check['domainname'] . " is claimed, but no claim key is present");
                }

            }
        }
    } else {
        echo "ERROR2\n";
    }
    return null;
}


/**
 * @param eppConnection $conn
 * @param string $domainname
 * @param array $claim
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $billingcontact
 * @param array $nameservers
 */
function createclaimeddomain($conn, $domainname, $claim, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    $domain = new eppDomain($domainname, $registrant);
    $domain->setPeriod(1);
    $reg = new eppContactHandle($registrant);
    $domain->setRegistrant($reg);
    if ($admincontact) {
        $admin = new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN);
        $domain->addContact($admin);
    }
    if ($techcontact) {
        $tech = new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH);
        $domain->addContact($tech);
    }
    if ($billingcontact) {
        $billing = new eppContactHandle($billingcontact, eppContactHandle::CONTACT_TYPE_BILLING);
        $domain->addContact($billing);
    }
    $domain->setAuthorisationCode($domain->generateRandomString(8));
    if (is_array($nameservers)) {
        foreach ($nameservers as $nameserver) {
            $host = new eppHost($nameserver);
            $domain->addHost($host);
        }
    }
    $create = new eppLaunchCreateDomainRequest($domain);
    $create->setLaunchPhase('claims');
    //$create->setLaunchCodeMark($domainname.';'.base64_encode(hash('sha512',$domainname.'MetaregistrarRocks!',true)),'Metaregistrar');
    $create->addLaunchClaim('tmch', $claim['noticeid'], $claim['notafter'], $claim['confirmed']);
    //echo $create->saveXML();
    if ($response = $conn->request($create)) {
        /* @var Metaregistrar\EPP\eppLaunchCreateDomainResponse $response */
        //echo $response->saveXML();
        echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        //echo "Registration phase: ".$response->getLaunchPhase()." and Application ID: ".$response->getLaunchApplicationID()."\n";
    }
}

/**
 * @param eppConnection $conn
 * @param string $domainname
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $billingcontact
 * @param array $nameservers
 * @throws eppException
 */
function createdomain($conn, $domainname, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    $domain = new eppDomain($domainname, $registrant);
    $reg = new eppContactHandle($registrant);
    $domain->setRegistrant($reg);
    $admin = new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN);
    $domain->addContact($admin);
    $tech = new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH);
    $domain->addContact($tech);
    $billing = new eppContactHandle($billingcontact, eppContactHandle::CONTACT_TYPE_BILLING);
    $domain->addContact($billing);
    $domain->setAuthorisationCode($domain->generateRandomString(8));
    if (is_array($nameservers))
    {
        foreach ($nameservers as $nameserver)
        {
            $host = new eppHost($nameserver);
            $domain->addHost($host);
        }
    }
    $create = new eppCreateDomainRequest($domain);
    if ($response = $conn->request($create)) {
        /* @var $response Metaregistrar\EPP\eppCreateResponse */
        echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
    }
}