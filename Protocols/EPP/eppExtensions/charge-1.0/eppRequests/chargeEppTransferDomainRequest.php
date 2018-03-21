<?php
namespace Metaregistrar\EPP;
/**
 * Class chargeEppTransferRequest
 * @package Metaregistrar\EPP
 *
 *
<extension>
<charge:agreement xmlns:charge="http://www.unitedtld.com/epp/charge-1.0">
<charge:set>
<charge:category name="AAAA">premium</charge:category>
<charge:type>price</charge:type>
<charge:amount command="transfer">20.0000</charge:amount>
</charge:set>
</charge:agreement>
</extension>
 */
class chargeEppTransferDomainRequest extends eppTransferRequest
{
    function __construct($operation, $object, $category, $name, $type, $price)
    {
        parent::__construct($operation, $object);
        $this->addTransferPriceAgreement($category,$name,$type,$price);
        $this->addSessionId();
    }
    function addTransferPriceAgreement($category, $name, $type, $price) {
        $agreement = $this->createElement('charge:agreement');
        //$this->setNamespace('charge', 'http://www.unitedtld.com/epp/charge-1.0',$agreement);
        $set = $this->createElement('charge:set');
        $category = $this->createElement('charge:category',$category);
        $category->setAttribute('name',$name);
        $set->appendChild($category);
        $set->appendChild($this->createElement('charge:type',$type));
        $amount = $this->createElement('charge:amount',$price);
        $amount->setAttribute('command','transfer');
        $set->appendChild($amount);
        $agreement->appendChild($set);
        $this->getExtension()->appendChild($agreement);
    }
}