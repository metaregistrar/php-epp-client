<?php
namespace Metaregistrar\EPP;
/**
 * Class chargeEppInfoDomainResponse
 * @package Metaregistrar\EPP
 *
<epp:extension>
    <charge:infData xmlns:charge="http://www.unitedtld.com/epp/charge-1.0" xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xmlns:launch="urn:ietf:params:xml:ns:launch-1.0">
        <charge:set>
            <charge:category name="AAAA">premium</charge:category>
            <charge:type>price</charge:type>
            <charge:amount command="transfer">100.0000</charge:amount>
            <charge:amount command="create">100.0000</charge:amount>
            <charge:amount command="renew">100.0000</charge:amount>
            <charge:amount command="update" name="restore">100.0000</charge:amount>
        </charge:set>
    </charge:infData>
</epp:extension>
 */
class chargeEppInfoDomainResponse extends eppInfoDomainResponse {
    /**
     * chargeEppInfoDomainResponse constructor
     */
    function __construct() {
        parent::__construct();
    }

    public function getCharges() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/charge:infData/charge:set');
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