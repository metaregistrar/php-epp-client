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
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:creData/contact:id');
    }

    /**
     *
     * @return string create_date
     */
    public function getContactCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:creData/contact:crDate');
    }

    /**
     *
     * @return eppContactHandle contacthandle
     */
    public function getContactHandle() {
        if ($handle = $this->queryPath('/epp:epp/epp:response/epp:resData/contact:creData/contact:id')) {
            return new eppContactHandle($handle);
        } else {
            return null;
        }
    }

}