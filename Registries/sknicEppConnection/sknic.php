<?php
require('../../autoloader.php');

use Metaregistrar\EPP\AuxContactCreateDomainRequest;
use Metaregistrar\EPP\AuxContactUpdateDomainRequest;
use Metaregistrar\EPP\eppDeleteContactRequest;
use Metaregistrar\EPP\eppDeleteDomainRequest;
use Metaregistrar\EPP\eppDeleteHostRequest;
use Metaregistrar\EPP\eppHelloRequest;
use Metaregistrar\EPP\eppHelloResponse;
use Metaregistrar\EPP\eppInfoContactRequest;
use Metaregistrar\EPP\eppInfoDomainRequest;
use Metaregistrar\EPP\eppInfoDomainResponse;
use Metaregistrar\EPP\eppInfoHostRequest;
use Metaregistrar\EPP\eppRenewRequest;
use Metaregistrar\EPP\eppRenewResponse;
use Metaregistrar\EPP\sknicEppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppCheckDomainRequest;
use Metaregistrar\EPP\eppCheckDomainResponse;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppContact;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppCheckContactRequest;
use Metaregistrar\EPP\eppCheckHostRequest;
use Metaregistrar\EPP\eppCreateHostRequest;
use Metaregistrar\EPP\eppPollRequest;
use Metaregistrar\EPP\eppPollResponse;
use Metaregistrar\EPP\sknicEppContactPostalInfo;
use Metaregistrar\EPP\sknicEppCreateContactRequest;
use Metaregistrar\EPP\sknicEppUpdateContactRequest;

/**
 * Get server information.
 *
 * @param sknicEppConnection $conn
 */
function hello($conn): array
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */

    $return = [
        'server_name' => null,
        'server_date' => null,
        'languages' => [],
        'versions' => [],
        'services' => [],
        'extensions' => [],
    ];

    try {
        $greeting = new eppHelloRequest;
        if ((($response = $conn->request($greeting)) instanceof eppHelloResponse) && ($response->Success())) {
            $return['server_name'] = $response->getServerName();
            $return['server_date'] = $response->getServerDate();
            $return['languages'] = $response->getLanguages();
            $return['versions'] = $response->getVersions();
            $return['services'] = $response->getServices();
            $return['extensions'] = $response->getExtensions();
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }

    return $return;
}

/**
 * Check for poll messages.
 *
 * @param sknicEppConnection $conn
 */
function checkPoll($conn)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $poll = new eppPollRequest(eppPollRequest::POLL_REQ);
        if ($response = $conn->request($poll)) {
            /* @var $response eppPollResponse */
            echo "You have ".$response->getMessageCount()." poll messages waiting\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Check domain availability.
 *
 * @param sknicEppConnection $conn
 * @param array $domains of domain names
 */
function checkDomains($conn, array $domains)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        // Create request to be sent to EPP service
        $check = new eppCheckDomainRequest($domains,false);
        // Write request to EPP service, read and check the results
        if ($response = $conn->request($check)) {
            /* @var $response eppCheckDomainResponse */
            // Walk through the results
            $checks = $response->getCheckedDomains();
            foreach ($checks as $check) {
                echo $check['domainname'] . " is " . ($check['available'] ? 'free' : 'taken');
                if ($check['available']) {
                    echo ' (' . $check['reason'] .')';
                }
                echo "\n";
            }
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Check if a contact exists.
 *
 * @param sknicEppConnection $conn
 * @param string $contactid
 */
function checkContact($conn, string $contactid)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $check = new eppCheckContactRequest(new eppContactHandle($contactid),false);
        if ($response = $conn->request($check)) {
            /* @var $response Metaregistrar\EPP\eppCheckContactResponse */
            //$response->dumpContents();
            $checks = $response->getCheckedContacts();
            foreach ($checks as $contact => $check) {
                echo "Contact $contact " . ($check ? 'does not exist' : 'exists') . "\n";
            }
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Check if a host exists.
 *
 * @param sknicEppConnection $conn
 * @param array $hosts
 * @return bool|null
 */
function checkHosts($conn, array $hosts)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $checkhost = array();
        foreach ($hosts as $host) {
            $checkhost[] = new eppHost($host);
        }
        $check = new eppCheckHostRequest($checkhost,false);
        if ($response = $conn->request($check)) {
            /* @var $response Metaregistrar\EPP\eppCheckHostResponse */
            $checks = $response->getCheckedHosts();
            $allchecksok = true;
            foreach ($checks as $hostname => $check) {
                echo "$hostname " . ($check ? 'does not exist' : 'exists') . "\n";
                if ($check) {
                    $allchecksok = false;
                }
            }
            return $allchecksok;
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * Get host information.
 *
 * @param sknicEppConnection $conn
 * @param string $hostName
 */
function getHostInfo($conn, string $hostName)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $infoRequest = new eppInfoHostRequest(new eppHost($hostName));

        if ($response = $conn->request($infoRequest)) {
            /* @var $response Metaregistrar\EPP\eppInfoHostResponse */
            $info = $response->getHost();
            echo "Host: " . $info->getHostname() . " has " . $info->getIpAddressCount() . " ip addresses\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Create a new host.
 *
 * @param sknicEppConnection $conn
 * @param string $hostName
 * @param array $ipAddresses
 */
function createHost($conn, string $hostName, array $ipAddresses = [])
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $create = new eppCreateHostRequest(new eppHost($hostName, $ipAddresses),false);
        if ($response = $conn->request($create)) {
            /* @var $response Metaregistrar\EPP\eppCreateHostResponse */
            echo "Host created on " . $response->getHostCreateDate() . " with name " . $response->getHostName() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Delete a host.
 *
 * @param sknicEppConnection $conn
 * @param string $hostName
 */
function deleteHost($conn, string $hostName)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $host = new eppHost($hostName);
        $deleteRequest = new eppDeleteHostRequest($host);

        if ($conn->request($deleteRequest)) {
            /* @var $response Metaregistrar\EPP\eppDeleteResponse */
            echo "Host " . $hostName . " deleted\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Get domain info.
 *
 * @param sknicEppConnection $conn
 * @param string $domain
 */
function getDomainInfo($conn, string $domain)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $infoRequest = new eppInfoDomainRequest(new eppDomain($domain));

        if ($response = $conn->request($infoRequest)) {
            /* @var $response Metaregistrar\EPP\AuxContactInfoDomainResponse */
            $info = $response->getDomain();
            if (method_exists($response, 'getRgpStatuses')) {
                /* @var $response Metaregistrar\EPP\eppRgpInfoDomainResponse */
                $rgpStatuses = $response->getRgpStatuses();
            } else {
                $rgpStatuses = [];
                $xpath = $response->xPath();
                $result = $xpath->query('/epp:epp/epp:response/epp:extension/rgp:infData/rgp:rgpStatus/@s');
                foreach ($result as $status) {
                    $rgpStatuses[] = $status->nodeValue;
                }
            }

            echo "Domain: " . $info->getDomainname() . " has " . $info->getContactLength() . " contacts + RGP statuses: " . implode(',', $rgpStatuses) .  "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Create a new domain.
 *
 * @param sknicEppConnection $conn
 * @param string $domain
 * @param array $ns
 * @param string $registrant
 * @param array $contacts
 * @param int $period
 * @param null $authCode
 * @param null|string $abuseId
 * @param null|string $dnsOperatorId
 */
function createDomain($conn, string $domain, array $ns, string $registrant, array $contacts, int $period = 1, $authCode = null, $abuseId = null, $dnsOperatorId = null)
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        // Prepare auxiliary contacts
        $auxContacts = [];

        if ($abuseId) {
            $auxContacts[] = ['type' => 'abuse', 'id' => $abuseId];
        }

        if ($dnsOperatorId) {
            $auxContacts[] = ['type' => 'dns-operator', 'id' => $dnsOperatorId];
        }

        $domain = new eppDomain($domain, $registrant, $contacts, $ns, $period, $authCode);
        $domainRequest = new AuxContactCreateDomainRequest($domain,false,false, false, $auxContacts);
        $domainRequest->dumpContents();
        if ($response = $conn->request($domainRequest)) {
            /* @var $response Metaregistrar\EPP\eppCreateDomainResponse */
            echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Update a new domain.
 *
 * @param sknicEppConnection $conn
 * @param string $domain
 * @param null|eppDomain $addInfo
 * @param null|eppDomain $removeInfo
 * @param null|eppDomain $updateInfo
 * @param array $addContacts
 * @param array $removeContacts
 */
function updateDomain($conn, string $domain, $addInfo = null, $removeInfo = null, $updateInfo = null, $addContacts = [], $removeContacts = [])
{
    /* @var $conn Metaregistrar\EPP\sknicEppConnection */
    try {
        $domainRequest = new AuxContactUpdateDomainRequest(new eppDomain($domain),$addInfo, $removeInfo, $updateInfo, false, false, true, $addContacts, $removeContacts);
        $domainRequest->dumpContents();
        if ($response = $conn->request($domainRequest)) {
            /* @var $response Metaregistrar\EPP\eppUpdateDomainResponse */
            echo "Domain " . $response->getResultDomainName() . " updated\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Delete a domain.
 *
 * @param sknicEppConnection $conn
 * @param string $domain
 */
function deleteDomain($conn, string $domain)
{
    try {
        $deleteRequest = new eppDeleteDomainRequest(new eppDomain($domain));

        if ($conn->request($deleteRequest)) {
            /* @var $response Metaregistrar\EPP\eppDeleteResponse */
            echo "Domain " . $domain . " deleted\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Renew a domain.
 *
 * @param sknicEppConnection $conn
 * @param string $domainname
 * @param int $years
 */
function renewDomain($conn, string $domainname, int $years = 1)
{
    try {
        $domain = new eppDomain($domainname);
        $domain->setPeriod($years);
        $domain->setPeriodUnit('y');
        $info = new eppInfoDomainRequest($domain);
        if ($response = $conn->request($info)) {
            /* @var $response eppInfoDomainResponse */
            $expdate = date('Y-m-d', strtotime($response->getDomainExpirationDate()));
            $renew = new eppRenewRequest($domain, $expdate);
            // Write request to EPP service, read and check the results
            /* @var $response eppRenewResponse */
            if (($response = $conn->request($renew)) && (int) $response->getResultCode() === 1000) {
                echo "Domain " . $response->getDomainName() . " has been renewed for ".$years." year(s) and has expiration date ".$response->getDomainExpirationDate()."\n";
            }
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Get contact information.
 *
 * @param sknicEppConnection $conn
 * @param string $contactId
 */
function getContactInfo($conn, string $contactId)
{
    try {
        $infoRequest = new eppInfoContactRequest(new eppContactHandle($contactId));

        if ($response = $conn->request($infoRequest)) {
            /* @var $response Metaregistrar\EPP\eppInfoContactResponse */
            $info = $response->getContact();
            echo "Contact: " . $info->getType() . " has " . $info->getId() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}
/**
 * Create a new contact.
 *
 * @param sknicEppConnection $conn
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $org
 * @param string $street
 * @param string $postcode
 * @param string $city
 * @param string $country
 * @param string $type
 * @param string $uid
 * @return string|null
 */
function createContact($conn, string $email, string $telephone, string $name, string $org, string $street, string $postcode, string $city, string $country, string $type, string $uid)
{
    try {
        $telephone = preg_replace('/^\+([0-9]{1,3})([0-9]+)$/', '+$1.$2', $telephone);

        $postalInfo = new sknicEppContactPostalInfo($name, $city, $country, $org, $street, '', $postcode, eppContact::TYPE_AUTO, $type, $uid);
        $contact = new eppContact($postalInfo, $email, $telephone, null, generateValidAuthCode());
        $contactRequest = new sknicEppCreateContactRequest($contact, false);

        $contactRequest->dumpContents();
        if ($response = $conn->request($contactRequest)) {
            /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
            echo "Created " . $response->getContactId() . "\n";

            return $response->getContactId();
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }

    return null;
}

/**
 * Update an existing contact.
 *
 * @param sknicEppConnection $conn
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $org
 * @param string $street
 * @param string $postcode
 * @param string $city
 * @param string $country
 * @param string $type
 * @param string $uid
 */
function updateContact($conn, string $contactid, string $email, string $telephone, string $name, string $org, string $street, string $postcode, string $city, string $country, string $type, string $uid)
{
    try {
        $telephone = preg_replace('/^\+([0-9]{1,3})([0-9]+)$/', '+$1.$2', $telephone);

        $postalInfo = new sknicEppContactPostalInfo($name, $city, $country, $org, $street, '', $postcode, eppContact::TYPE_AUTO, $type, $uid);
        $updateInfo = new eppContact($postalInfo, $email, $telephone, null, generateValidAuthCode());
        $contact = new eppContactHandle($contactid);
        $contactRequest = new sknicEppUpdateContactRequest($contact, null, null, $updateInfo, false);

        if ($response = $conn->request($contactRequest)) {
            /* @var $response Metaregistrar\EPP\eppUpdateContactResponse */
            echo "Contact " . $response->getResultContactId() . " updated\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Delete a contact.
 *
 * @param sknicEppConnection $conn
 * @param string $contactId
 */
function deleteContact($conn, string $contactId)
{
    try {
        $deleteRequest = new eppDeleteContactRequest(new eppContactHandle($contactId));

        if ($conn->request($deleteRequest)) {
            /* @var $response Metaregistrar\EPP\eppDeleteResponse */
            echo "Contact " . $contactId . " deleted\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * Generate a valid authInfo code for EPP
 * - At least 16 characters long
 * - Contains at least one non-alphanumeric character
 *
 * @param  int  $length  Minimum length of the authInfo code
 * @return string Valid authInfo code
 */
function generateValidAuthCode(int $length = 16): string
{
    // Special characters, numbers, lowercase and uppercase characters
    $specialChars = '!@#$%^&*()-_=+{}[]|:;<>,.?/~';
    $numbers = '0123456789';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Ensure minimum length is at least 4 to fit one of each type
    if ($length < 4) {
        throw new \InvalidArgumentException('Length must be at least 4 to accommodate all required character types.');
    }

    // Generate the required characters
    $specialChar = $specialChars[random_int(0, strlen($specialChars) - 1)];
    $number = $numbers[random_int(0, strlen($numbers) - 1)];
    $lower = $lowercase[random_int(0, strlen($lowercase) - 1)];
    $upper = $uppercase[random_int(0, strlen($uppercase) - 1)];

    // Generate the remaining random characters (length - 4) from all characters
    $remainingLength = $length - 4;

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $remainingString = '';
    for ($i = 0; $i < $remainingLength; $i++) {
        $remainingString .= $characters[random_int(0, $charactersLength - 1)];
    }

    // Combine all characters
    $authCode = $lower.$upper.$number.$specialChar.$remainingString;

    // Shuffle the result to randomize the character positions
    return str_shuffle($authCode);
}

/*
 * Generate random domain names for testing
 * You can specify the number of domains to generate and an array of existing domains to avoid duplicates
 */
function generateRandomDomainSK($count = 1, $ext = '.sk') {
    $domains = [];
    $consonants = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'z'];
    $vowels = ['a', 'e', 'i', 'o', 'u', 'y'];

    while (count($domains) < $count) {
        $length = random_int(5, 12);
        $domain = '';

        // Generate domain with alternating consonants and vowels for better pronounceability
        for ($i = 0; $i < $length; $i++) {
            $domain .= ($i % 2 == 0) ? $consonants[array_rand($consonants)] : $vowels[array_rand($vowels)];
        }

        $domainWithTLD = $domain . $ext;

        // Check if domain already exists in our list or in existing domains
        if (!in_array($domainWithTLD, $domains)) {
            $domains[] = $domainWithTLD;
        }
    }

    return $domains;
}


/**
 * This script demonstrates how to use the EPP client to check domain availability,
 * create a contact, and create a domain with specified nameservers.
 *
 * It uses the Metaregistrar EPP client library.
 *
 * Usage:
 * php sknic.php
 */

$domains = generateRandomDomainSK();

/*
 * This script checks for the availability of domain names
 * You can specify multiple domain names to be checked
 */
try {

    if ($conn = sknicEppConnection::create('settings.ini', false)) {
        // Connect and login to the EPP server
        if ($conn->login()) {
            echo "Hellol\n";
            $hello = hello($conn);
            var_dump($hello);

            echo "Checking poll\n";
            checkPoll($conn);

            echo "Checking " . count($domains) . " domain names\n";
            checkDomains($conn, $domains);

            echo "Checking contact\n";
            checkContact($conn,'UBOM-0334');

            echo "Checking hosts\n";
            checkHosts($conn,['ns1.unknow.sk','ns2.unknow.sk','ns3.unknow.sk']);

            echo "Creating hosts\n";
            $host_id1 = createHost($conn,'ns1.unknow.sk', ['1.2.3.4']);
            $host_id2 = createHost($conn,'ns2.unknow.sk', ['5.6.7.8']);

            echo "Checking hosts after create\n";
            checkHosts($conn,['ns1.unknow.sk','ns2.unknow.sk','ns3.unknow.sk']);

            echo "Get host info\n";
            getHostInfo($conn, 'ns1.unknow.sk');

            echo "Removing hosts\n";
            deleteHost($conn, 'ns1.unknow.sk');
            deleteHost($conn, 'ns2.unknow.sk');

            echo "Checking hosts after delete\n";
            checkHosts($conn,['ns1.unknow.sk','ns2.unknow.sk','ns3.unknow.sk']);

            echo "Creating contact\n";
            $contactid = createcontact($conn, 'admin@unknow.sk','+421.901234567','IT' ,'Unknow.sk' ,'Pod mostom 1' ,'05201' , 'Spišská Nová Ves', 'SK', 'PERS', '1987-06-09');

            if ($contactid) {
                echo "Get contact info\n";
                getContactInfo($conn, $contactid);

                echo "Updating contact\n";
                updateContact($conn, $contactid, 'admin@unknow.sk','+421.901234567','IT' ,'Unknow.sk' ,'Pod mostom 1' ,'05201' , 'Spišská Nová Ves', 'SK', 'PERS', '1987-06-05');

                echo "Removing contact\n";
                deleteContact($conn, $contactid);
            }

            echo "Creating domain\n";
            createDomain(
                $conn,
                $domains[0],
                [
                    new eppHost('ns1.unknow.sk'),
                    new eppHost('ns2.unknow.sk')
                ],
                $conn->getUsername(),
                [
                    new eppContactHandle($conn->getUsername(), eppContactHandle::CONTACT_TYPE_ADMIN),
                    new eppContactHandle($conn->getUsername(), eppContactHandle::CONTACT_TYPE_TECH),
                    new eppContactHandle($conn->getUsername(), eppContactHandle::CONTACT_TYPE_BILLING)
                ],
                2,
                generateValidAuthCode()
            );

            echo "Updating domain\n";
            $updateInfo = new eppDomain($domains[0]);

            updateDomain(
                $conn,
                $domains[0],
                null,
                null,
                $updateInfo,
                [
                    ['type' => 'abuse', 'id' => $conn->getUsername()]
                ],
                [
                    ['type' => 'abuse', 'id' => $conn->getUsername()]
                ]
            );

            echo "Get domain info\n";
            getDomainInfo($conn, $domains[0]);

            echo "Renewing domain\n";
            renewDomain($conn, $domains[0], 1);

            echo "Removing domain\n";
            deleteDomain($conn, $domains[0]);

            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage()."\n";
    echo $e->getLastCommand();
    echo "\n\n";
}
