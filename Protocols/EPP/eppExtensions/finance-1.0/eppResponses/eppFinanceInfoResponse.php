<?php
namespace Metaregistrar\EPP;

/**
 * Class eppFinanceInfoResponse
 * @package Metaregistrar\EPP
 *
<resData>
<finance:infData>
<finance:balance>98990627.00</finance:balance>
</finance:infData>
</resData>

 */
class eppFinanceInfoResponse extends eppResponse {

    function __construct() {
        parent::__construct();
    }

    public function getBalance() {
        $xpath = $this->xPath();
        $result = $xpath->query('//finance:balance');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}
