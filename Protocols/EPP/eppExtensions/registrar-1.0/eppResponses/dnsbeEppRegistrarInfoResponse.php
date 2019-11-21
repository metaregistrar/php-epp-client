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

    public function getBalance() {
        $xpath = $this->xPath();
        $result = $xpath->query('//dnssi:balance');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}