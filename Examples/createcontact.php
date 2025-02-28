<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppContactPostalInfo;
use Metaregistrar\EPP\eppContact;
use Metaregistrar\EPP\eppCreateContactRequest;
use Metaregistrar\EPP\rrpproxyEppConnection;
use Metaregistrar\EPP\rrpproxyEppCreateContactRequest;
use Metaregistrar\EPP\rrpproxyEppCreateContactResponse;

/**
 * This code example creates a contact object with a registry
 */

try {
// Please enter your own settings file here under before using this example
    if ($conn = rrpproxyEppConnection::create('settings.ini')) {
        // Connect to the EPP server
        if ($conn->login()) {
            createcontact($conn, 'info@test.com', '+31.201234567', 'Domain Administration', 'Metaregistrar', 'Address 1', 'Zipcode', 'City', 'NL');
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}



/**
 * @param $conn eppConnection
 * @param $email string
 * @param $telephone string
 * @param $name string
 * @param $organization string
 * @param $address string
 * @param $postcode string
 * @param $city string
 * @param $country string
 * @return null
 */
function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode);
    $contactinfo = new eppContact($postalinfo, $email, $telephone);
    $contactinfo->setPassword('');
    try {
        $contact = new rrpproxyEppCreateContactRequest($contactinfo);
        $contact->setVerified(1);
        $contact->setValidated(1);
        if ($response = $conn->request($contact)) {
            /* @var $response Metaregistrar\EPP\rrpproxyEppCreateContactResponse */
            echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
            return $response->getContactId();
        } else {
            echo "Create contact failed";
        }
    } catch (eppException $e) {
        echo "ERROR: " . $e->getMessage() . "\n\n";
        return false;
    }
    return null;
}