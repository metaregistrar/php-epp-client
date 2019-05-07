<?php
namespace Metaregistrar\EPP;
/**
 * Class chargeEppCheckDomainResponse
 * @package Metaregistrar\EPP
 *
<extension>
    <charge:chkData xmlns:charge="http://www.unitedtld.com/epp/charge-1.0">
        <charge:cd>
            <charge:name>greatname.TLD</charge:name>
            <charge:set>
                <charge:category name="AAAA">premium</charge:category>
                <charge:type>price</charge:type>
                <charge:amount command="create">20.0000</charge:amount>
                <charge:amount command="renew">20.0000</charge:amount>
                <charge:amount command="transfer">20.0000</charge:amount>
                <charge:amount command="update" name="restore">20.0000</charge:amount>
            </charge:set>
        </charge:cd>
        <charge:cd>
            <charge:name>greatname2.TLD</charge:name>
            <charge:set>
                <charge:category name="AAAA">premium</charge:category>
                <charge:type>price</charge:type>
                <charge:amount command="create">20.0000</charge:amount>
                <charge:amount command="renew">20.0000</charge:amount>
                <charge:amount command="transfer">20.0000</charge:amount>
                <charge:amount command="update" name="restore">20.0000</charge:amount>
            </charge:set>
        </charge:cd>
    </charge:chkData>
</extension>
 */
class chargeEppCheckDomainResponse extends eppCheckDomainResponse {
    /**
     * chargeEppCheckDomainResponse constructor.
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Get all charges for all domain names
     * Return is in an array of chargeEppDomain objects
     * @return array|null
     */
    public function getCharges() {
        $response = [];
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/charge:chkData/*');
        if ($result->length > 0) {
            foreach ($result as $record) {
                /* @var \DOMElement $record */
                $domainname = $record->getElementsByTagName('name')->item(0)->nodeValue;
                $thischarge = new chargeEppDomain();

                $category = $record->getElementsByTagName('category')->item(0);
                /* @var \DOMElement $category */
                $thischarge->setCategoryname($category->nodeValue);
                $thischarge->setCategoryid($category->getAttribute('name'));
                $thischarge->setChargetype($record->getElementsByTagName('type')->item(0)->nodeValue);
                $charges = $record->getElementsByTagName('amount');
                $c = [];
                foreach ($charges as $charge) {
                    /* @var \DOMElement $charge */
                    $amount = $charge->nodeValue;
                    $command = $charge->getAttribute('command');
                    $c[$command]=$amount;
                }
                $thischarge->setCharges($c);
                $response[$domainname] = $thischarge;
            }
            return $response;
        } else {
            return null;
        }
    }

    /**
     * Get the premium prices for a specific domain name
     * @param string $domainname
     * @return null|chargeEppDomain
     */
    public function getChargeForDomainName($domainname) {
        $response = $this->getCharges();
        if (isset($response[$domainname])) {
            return $response[$domainname];
        } else {
            return null;
        }
    }

}