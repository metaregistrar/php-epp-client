<?php
namespace Metaregistrar\EPP;
/**
 * Class chargeEppCreateDomainResponse
 * @package Metaregistrar\EPP
 *
**/

class chargeEppCreateDomainResponse extends eppCreateDomainResponse {
    /**
     * chargeEppCreateDomainResponse constructor.
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Retrieve charges for the created domain name
     * @return chargeEppDomain|null
     */
    public function getCharges() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/charge:agreement/charge:set');
        if ($result->length > 0) {
            /* @var \DOMElement $result */
            $category = $result->getElementsByTagName('category')->item(0);
            /* @var \DOMElement $category */
            $thischarge = new chargeEppDomain();
            $thischarge->setCategoryname($category->nodeValue);
            $thischarge->setCategoryid($category->getAttribute('name'));
            $thischarge->setChargetype($result->getElementsByTagName('type')->item(0)->nodeValue);
            $charges = $result->getElementsByTagName('amount');
            $c = [];
            foreach ($charges as $charge) {
                /* @var \DOMElement $charge */
                $amount = $charge->nodeValue;
                $command = $charge->getAttribute('command');
                $c[$command]=$amount;
            }
            $thischarge->setCharges($c);
            return $thischarge;
        } else {
            return null;
        }
    }

}
