<?php
namespace Metaregistrar\EPP;

class eppCreateContactResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    /**
     * CONTACT CREATE RESPONSES
     */

    /**
     *
     * @return string contact_id
     */
    public function getContactId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:creData/contact:id');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    /**
     *
     * @return string create_date
     */
    public function getContactCreateDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:creData/contact:crDate');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    /**
     *
     * @return eppContactHandle contacthandle
     */
    public function getContactHandle() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:creData/contact:id');
        $contacthandle = new eppContactHandle($result->item(0)->nodeValue);
        return $contacthandle;
    }

}