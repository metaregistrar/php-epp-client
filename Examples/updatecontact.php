<?php
require('../autoloader.php');


try {
    $conn = new Metaregistrar\EPP\metaregEppConnection();
    $conn->setConnectionDetails('');
    // Connect to the EPP server
    if ($conn->connect()) {
        if ($conn->login()) {
            echo "Creating contact\n";
            $contactid = createcontact($conn,'test@test.com','+31.61234567890','Person name',null,'Address 1','12345','City','NL');
            echo "Updating $contactid\n";
            updatecontact($conn,$contactid,'up@hostmax.ch','+31.20123456789','Updates name','Updated org','Updated address 1','12345','City','NL');
            logout($conn);
        }
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo $e->getMessage() . "\n";
    $conn->logout();
}


function updatecontact($conn, $contactid, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    /* @var $conn Metaregistrar\EPP\eppConnection.php */
    try {
        $contact = new Metaregistrar\EPP\eppContactHandle($contactid);
        $update = new Metaregistrar\EPP\eppContact();
        $update->setVoice($telephone);
        $update->setEmail($email);
        $pi = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $update->addPostalInfo($pi);
        $up = new Metaregistrar\EPP\eppUpdateContactRequest($contact, null, null, $update);
        if ((($response = $conn->writeandread($up)) instanceof Metaregistrar\EPP\eppUpdateResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Contact $contactid updated, infoing\n";
            //infocontact($conn, $contactid);
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        print_r($e);


        echo $e->getMessage() . "\n";
    }
}



function createcontact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country) {
    /* @var $conn Metaregistrar\EPP\eppConnection.php */
    try {
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contact = new Metaregistrar\EPP\EppCreateContactRequest($contactinfo);
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