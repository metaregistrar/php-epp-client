<?php

require('../autoloader.php');

/*
 * This sample script registers a domain name within your account
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */

$now = $current_date = gmDate("Y-m-d\TH:i:s\Z");
$claims = array(
    'test-claims-1.frl' => array('noticeid' => '2a87fdbb9223372036854775807', 'notafter' => '2019-09-04T07:47:03.123Z', 'lookup' => '2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX4R2127', 'confirmed' => $now),
    'test-claims-2.frl' => array('noticeid' => 'e434f0f59223372036854775807', 'notafter' => '2018-10-01T15:40:13.843Z', 'lookup' => '2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX4R2609', 'confirmed' => $now),
    'test-claims-3.frl' => array('noticeid' => '3d2f541d9223372036854775807', 'notafter' => '2018-11-06T08:17:08.8Z', 'lookup' => '2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX3R2333', 'confirmed' => $now),
    'a-b-c-d-e-fg.amsterdam' => array('noticeid' => '27d5501a0000000000000407286', 'notafter' => '2015-05-30T00:00:00.0Z', 'lookup' => '2015052800/9/6/9/lpexfNxa2c0WNTKtzWXsizak0000000408', 'confirmed' => $now),
    'a-b-c-d-ef-g.amsterdam' => array('noticeid' => 'f9378df20000000000000407484', 'notafter' => '2015-05-30T00:00:00.0Z', 'lookup' => '2015052800/8/F/1/jxDmzfePmZekFgnG9dI8F0zG0000000606', 'confirmed' => $now)

);

$domainname = 'a-b-c-d-ef-g.amsterdam';
echo "Registering $domainname\n";

$conn = new Metaregistrar\EPP\metaregEppConnection();
$conn->enableLaunchphase('claims');

// Connect to the EPP server
if ($conn->connect()) {
    if (login($conn)) {
        $contactid = '267';
        $contactid = '267';
        $techcontact = '267';
        //$billingcontact = '477';
        //$claim = checkdomainclaim($conn,$domainname);
        createclaimeddomain($conn, $domainname, $claims[$domainname], $contactid, $contactid, $techcontact, $billingcontact, array('ns1.metaregistrar.nl','ns2.metaregistrar.nl'));
        logout($conn);
    }
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 * @return array|null
 */
function checkdomainclaim($conn, $domainname) {
    try {
        $check = new Metaregistrar\EPP\eppLaunchCheckRequest(array($domainname));
        $check->setLaunchPhase(Metaregistrar\EPP\eppLaunchCheckRequest::PHASE_CLAIMS, null, Metaregistrar\EPP\eppLaunchCheckRequest::TYPE_CLAIMS);
        if ((($response = $conn->writeandread($check)) instanceof Metaregistrar\EPP\eppLaunchCheckResponse) && ($response->Success())) {
            //$phase = $response->getLaunchPhase();
            /* @var Metaregistrar\EPP\eppLaunchCheckResponse $response */
            $checks = $response->getDomainClaims();

            foreach ($checks as $check) {
                echo $check['domainname'] . " has " . ($check['claimed'] ? 'a claim' : 'no claim') . "\n";
                if ($check['claimed']) {
                    if ($check['claim']) {
                        if ($check['claim'] instanceof Metaregistrar\EPP\eppDomainClaim) {
                            echo "Claim validator: " . $check['claim']->getValidator() . ", claim key: " . $check['claim']->getClaimKey() . "\n";
                            $tmch = new Metaregistrar\TMCH\cnisTmchConnection();
                            $claim = array();
                            $output = $tmch->getCnis($check['claim']->getClaimKey());
                            /* @var $output Metaregistrar\TMCH\tmchClaimData */
                            $claim['noticeid']= $output->getNoticeId();
                            $claim['notafter']= $output->getNotAfter();
                            $claim['confirmed']= gmDate("Y-m-d\TH:i:s\Z");
                            return $claim;
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
    } catch (Metaregistrar\EPP\eppException $e) {
        echo 'ERROR1: ' . $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 * @param Metaregistrar\TMCH\tmchClaimData $claim
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $billingcontact
 * @param array $nameservers
 */
function createclaimeddomain($conn, $domainname, $claim, $registrant, $admincontact, $techcontact, $billingcontact, $nameservers) {
    try {
        $domain = new Metaregistrar\EPP\eppDomain($domainname, $registrant);
        $domain->setPeriod(1);
        $reg = new Metaregistrar\EPP\eppContactHandle($registrant);
        $domain->setRegistrant($reg);
        if ($admincontact) {
            $admin = new Metaregistrar\EPP\eppContactHandle($admincontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN);
            $domain->addContact($admin);
        }
        if ($techcontact) {
            $tech = new Metaregistrar\EPP\eppContactHandle($techcontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH);
            $domain->addContact($tech);
        }
        if ($billingcontact) {
            $billing = new Metaregistrar\EPP\eppContactHandle($billingcontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING);
            $domain->addContact($billing);
        }
        $domain->setAuthorisationCode($domain->generateRandomString(8));
        if (is_array($nameservers)) {
            foreach ($nameservers as $nameserver) {
                $host = new Metaregistrar\EPP\eppHost($nameserver);
                $domain->addHost($host);
            }
        }
        $create = new Metaregistrar\EPP\eppLaunchCreateDomainRequest($domain);
        $create->setLaunchPhase('claims');
        //$create->setLaunchCodeMark($domainname.';'.base64_encode(hash('sha512',$domainname.'MetaregistrarRocks!',true)),'Metaregistrar');
        $create->addLaunchClaim('tmch', $claim['noticeid'], $claim['notafter'], $claim['confirmed']);
        echo $create->saveXML();
        if ((($response = $conn->writeandread($create)) instanceof Metaregistrar\EPP\eppLaunchCreateDomainResponse) && ($response->Success())) {
            /* @var Metaregistrar\EPP\eppLaunchCreateDomainResponse $response */
            echo $response->saveXML();
            echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
            //echo "Registration phase: ".$response->getLaunchPhase()." and Application ID: ".$response->getLaunchApplicationID()."\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}