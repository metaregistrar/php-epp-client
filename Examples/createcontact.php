<?php
require('../autoloader.php');


$conn = new Metaregistrar\EPP\metaregEppConnection();

// Connect to the EPP server
if ($conn->connect()) {
    if (login($conn)) {
        createcontact($conn,'info@fryslan.frl','+31.582925925','Domain Administration','Provincie Fryslân','Tweebaksmarkt 52','8911 KZ','Leeuwarden','NL');
        logout($conn);

    }

}


function createcontact($conn,$email,$telephone,$name,$organization,$address,$postcode,$city, $country)
{
    try
    {
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, Metaregistrar\EPP\eppContactPostalInfo::POSTAL_TYPE_INTERNATIONAL);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contact = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof Metaregistrar\EPP\eppCreateResponse) && ($response->Success()))
        {
            echo "Contact created on ".$response->getContactCreateDate()." with id ".$response->getContactId()."\n";
            return $response->getContactId();
        }
    }
    catch (Metaregistrar\EPP\eppException $e)
    {
        echo $e->getMessage()."\n";
    }
    return null;
}