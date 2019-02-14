<?php
namespace Metaregistrar\EPP;
/**
 * Class chargeEppCreateDomainRequest
 * @package Metaregistrar\EPP
 *
 *
<extension>
    <charge:agreement xmlns:charge="http://www.unitedtld.com/epp/charge-1.0">
        <charge:set>
            <charge:category name="AAAA">premium</charge:category>
            <charge:type>price</charge:type>
            <charge:amount command="create">20.0000</charge:amount>
        </charge:set>
    </charge:agreement>
</extension>
 */
class chargeEppCreateDomainRequest extends eppCreateDomainRequest {

    /**
     * chargeEppCreateDomainRequest constructor.
     * @param $createinfo
     * @param bool $forcehostattr
     * @param bool $namespacesinroot
     */
    function __construct($createinfo,  $forcehostattr = false, $namespacesinroot = true) {
        parent::__construct($createinfo, $forcehostattr, $namespacesinroot);
    }

    /**
     * Add the necessary charges to the created domain name
     * @param chargeEppDomain $charge
     */
    function addDomainCharge(chargeEppDomain $charge) {
        $agreement = $this->createElement('charge:agreement');
        //$this->setNamespace('charge', 'http://www.unitedtld.com/epp/charge-1.0',$agreement);
        $set = $this->createElement('charge:set');
        $category = $this->createElement('charge:category',$charge->getCategoryname());
        $category->setAttribute('name',$charge->getCategoryid());
        $set->appendChild($category);
        $set->appendChild($this->createElement('charge:type',$charge->getChargetype()));
        $amount = $this->createElement('charge:amount',$charge->getCharge('create'));
        $amount->setAttribute('command','create');
        $set->appendChild($amount);
        $agreement->appendChild($set);
        $this->getExtension()->appendChild($agreement);
        $this->addSessionId();
    }
}
