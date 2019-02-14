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

    public function getCharges() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/charge:agreement/charge:set');
        if ($result->length > 0) {
            /* @var \DOMElement $result */
            $category = $result->getElementsByTagName('category')->item(0);
            /* @var \DOMElement $category */
            $categoryname = $category->nodeValue;
            $categoryid = $category->getAttribute('name');
            $charges = $result->getElementsByTagName('amount');
            $chargetype = $result->getElementsByTagName('type')->item(0)->nodeValue;
            $c = [];
            foreach ($charges as $charge) {
                /* @var \DOMElement $charge */
                $amount = $charge->nodeValue;
                $command = $charge->getAttribute('command');
                $c[$command]=$amount;
            }
            $response = ['categoryname'=>$categoryname,'categoryid'=>$categoryid,'chargetype'=>$chargetype,'charges'=>$c];
            return $response;
        } else {
            return null;
        }
    }

}
