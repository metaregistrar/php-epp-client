<?php
namespace Metaregistrar\EPP;
/**
 * Class euridEppRegistrarInfoResponse
 * @package Metaregistrar\EPP
 *
 */
class dnsbeEppRegistrarInfoResponse extends eppResponse {

    function __construct() {
        parent::__construct();
    }

    public function getAmountAvailable() {
        $xpath = $this->xPath();
        $result = $xpath->query('//dnsbe:amountAvailable');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getHitPoints() {
        $xpath = $this->xPath();
        $result = $xpath->query('//dnsbe:nbrHitPoints');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getMaxHitPoints() {
        $xpath = $this->xPath();
        $result = $xpath->query('//dnsbe:maxNbrHitPoints');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

}