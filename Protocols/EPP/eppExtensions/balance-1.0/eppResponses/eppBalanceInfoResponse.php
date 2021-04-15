<?php
namespace Metaregistrar\EPP;
/**
 * Class eppBalanceInfoResponse
 * @package Metaregistrar\EPP
 *
<balance:infData xmlns:balance="http://www.verisign.com/epp/balance-1.0">
    <balance:creditLimit>1000.00</balance:creditLimit>
    <balance:balance>200.00</balance:balance>
    <balance:availableCredit>800.00</balance:availableCredit>
    <balance:creditThreshold>
        <balance:fixed>500.00</balance:fixed>
    </balance:creditThreshold>
</balance:infData>
 */
class eppBalanceInfoResponse extends eppResponse {
    /**
     * eppBalanceInfoResponse constructor
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * @return string|null
     */
    public function getCreditLimit(): ?string {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/balance:infData/balance:creditLimit');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getBalance(): ?string {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/balance:infData/balance:balance');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getAvailableCredit(): ?string {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/balance:infData/balance:availableCredit');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getCreditTresholdFixed(): ?string {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/balance:infData/balance:creditThreshold/balance:fixed');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getCreditTresholdPercent(): ?string {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/balance:infData/balance:creditThreshold/balance:percent');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}