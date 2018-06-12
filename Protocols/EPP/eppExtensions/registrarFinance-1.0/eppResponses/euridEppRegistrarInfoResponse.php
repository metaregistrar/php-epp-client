<?php
namespace Metaregistrar\EPP;
/**
 * Class euridEppRegistrarInfoResponse
 * @package Metaregistrar\EPP
 *
<resData>
    <registrarFinance:infData>
        <registrarFinance:paymentMode>PRE_PAYMENT</registrarFinance:paymentMode>
        <registrarFinance:availableAmount>999990.62</registrarFinance:availableAmount>
        <registrarFinance:accountBalance>987890.62</registrarFinance:accountBalance>
    </registrarFinance:infData>
 </resData>

 */
class euridEppRegistrarInfoResponse extends eppResponse {

    function __construct() {
        parent::__construct();
    }
    
    public function getPaymentMode() {
        $xpath = $this->xPath();
        $result = $xpath->query('//registrarFinance:paymentMode');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        } 
    }

    public function getAmount() {
        $xpath = $this->xPath();
        $result = $xpath->query('//registrarFinance:availableAmount');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        } 
    }

    public function getBalance() {
        $xpath = $this->xPath();
        $result = $xpath->query('//registrarFinance:accountBalance');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}
