<?php
namespace Metaregistrar\EPP;

/**
 * Class dnsbePollDomainResponse
 * @package Metaregistrar\EPP
 */
class dnsbeEppPollResponse extends eppPollResponse {
    /**
     * Retrieve the action from the poll response message
     * @return string|null
     */
    public function getAction() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:action');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * Retrieve the contact id from the poll response message
     * @return string|null
     */
    public function getContact() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:contact');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * Retrieve the contact id from the poll response message
     * @return string|null
     */
    public function getDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:date');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * Retrieve the contact id from the poll response message
     * @return string|null
     */
    public function getType() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:type');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * Retrieve the warning level from the poll response message
     *
     * @return string|null
     *
     * Package: Metaregistrar\EPP
     */
    public function getLevel() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:level');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getDomainName()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:domainname');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}

