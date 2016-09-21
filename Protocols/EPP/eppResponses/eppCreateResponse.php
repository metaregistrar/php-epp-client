<?php
namespace Metaregistrar\EPP;

class eppCreateResponse extends eppResponse {
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
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:creData/contact:id');
        $contacthandle = new eppContactHandle($result->item(0)->nodeValue);
        return $contacthandle;
    }

    /**
     * DOMAIN CREATE RESPONSES
     */

    public function getDomainCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:creData/domain:crDate');
    }


    public function getDomainExpirationDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:creData/domain:exDate');
    }


    public function getDomainName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:creData/domain:name');
    }

    public function getDomain() {
        $return = new eppDomain($this->getDomainName());
        return $return;
    }

    /**
     * HOST CREATE RESPONSES
     */


    public function getHostName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:creData/host:name');
    }

    public function getHostCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:creData/host:crDate');
    }
}