<?php
require('../autoloader.php');


$conn = new Metaregistrar\EPP\metaregEppConnection();
$conn->setConnectionDetails('');
// Connect to the EPP server
if ($conn->connect()) {
    if ($conn->login()) {
        createcontact($conn, 'info@test.com', '+31.201234567', 'Domain Administration', 'Metaregistrar', 'Address 1', 'Zipcode', 'City', 'NL');
        $conn->logout();
    }
}
/**
 * @param $conn Metaregistrar\EPP\eppConnection
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
    try {
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contactinfo->setPassword('');
        $contact = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
            return $response->getContactId();
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}