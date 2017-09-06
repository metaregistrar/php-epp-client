<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppContact;
use Metaregistrar\EPP\eppUpdateContactRequest;
use Metaregistrar\EPP\eppContactPostalInfo;

try {
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            echo "Creating contact\n";
            $contactid = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
            echo "Updating contact #$contactid\n";
            updatecontact($conn,$contactid,'up@hostmax.ch','+31.20123456789','Updates name','Updated org','Updated address 1','12345','City','NL');
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}

/**
 * @param eppConnection $conn
 * @param string $contactid
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 */
function updatecontact($conn, $contactid, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    try {
        $contact = new eppContactHandle($contactid);
        $update = new eppContact(new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContact::TYPE_LOC),$email,$telephone);
        $up = new eppUpdateContactRequest($contact, null, null, $update);
        if ($response = $conn->request($up)) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Contact $contactid updated.\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param eppConnection $conn
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 * @return null|string
 */
function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    try {
        $contact = new Metaregistrar\EPP\eppContact(new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC), $email, $telephone);
        $create = new Metaregistrar\EPP\EppCreateContactRequest($contact);
        if ($response = $conn->request($create)) {
            /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
            echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
            return $response->getContactId();
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}