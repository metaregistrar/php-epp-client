<?php
namespace Metaregistrar\EPP;

class noridEppHost extends eppHost {

    private $extContact = null;
    private $extSponsoringClientID = null;

    function __construct($hostname, $ipaddress = null, $hoststatus = null, $extContact = null, $extSponsoringClientID = null) {
        parent::__construct($hostname, $ipaddress, $hoststatus);
        $this->setExtContact($extContact);
        $this->setExtSponsoringClientID($extSponsoringClientID);
    }

    public function setExtContact($contact) {
        if (!is_null($contact)) {
            $this->extContact = $contact;
        }
    }

    public function getExtContact() {
        return $this->extContact;
    }

    public function setExtSponsoringClientID($clientId) {
        if (!is_null($clientId)) {
            $this->extSponsoringClientID = $clientId;
        }
    }

    public function getExtSponsoringClientID() {
        return $this->extSponsoringClientID;
    }

}