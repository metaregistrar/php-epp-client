<?php
namespace Metaregistrar\EPP;

class euridEppInfoDomainResponse extends eppInfoDomainResponse {
    function __construct() {
        parent::__construct();
    }


    /**
     * Return EURid specific contact type 'onsite', which is not EPP standard
     *
     * @return array eppContactHandles
     */
    public function getDomainContacts() {
        $xpath = $this->xPath();
        $cont = null;
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:contact');
        foreach ($result as $contact) {
            /* @var $contact \DOMElement */
            $contacttype = $contact->getAttribute('type');
            if ($contacttype) {
                $cont[] = new eppContactHandle($contact->nodeValue, $contacttype);
            }
        }
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:contact');
        foreach ($result as $contact) {
            /* @var $contact \DOMElement */
            $contacttype = $contact->getAttribute('type');
            if ($contacttype) {
                // EURID specific
                if ($contacttype == 'onsite') {
                    $contacttype = 'admin';
                }
                $cont[] = new eppContactHandle($contact->nodeValue, $contacttype);
            }
        }
        return $cont;
    }

    /**
     * Get the date until the auth code is valie
     * @return null|string
     */
    public function getAuthorisationCodeValidDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/authInfo:infData/authInfo:validUntil');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }


    /**
     *
     * @return boolean
     */
    public function getQuarantined() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/eurid:ext/eurid:infData/eurid:domain/eurid:quarantined');
        if ($result->length > 0) {
            if ($result->item(0)->nodeValue == 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }


    /**
     *
     * @return boolean
     */
    public function getOnHold() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/eurid:ext/eurid:infData/eurid:domain/eurid:onhold');
        if ($result->length > 0) {
            if ($result->item(0)->nodeValue == 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }
}

