<?php
namespace Metaregistrar\EPP;


class atEppUpdateContactExtension extends atEppExtensionChain
{
    protected $atEppContact=null;
    protected $persTypeChanged=true;

    function __construct(atEppContact $atEppContact, ?atEppExtensionChain $additionalEppExtension=null, bool $persTypeChanged = true) {
        if(!is_null($additionalEppExtension)) {
            parent::__construct($additionalEppExtension);
        }
        $this->atEppContact = $atEppContact;
        $this->persTypeChanged = $persTypeChanged;
    }


    public function setEppRequestExtension(eppRequest $request,\DOMElement $extension)
    {
        $request->addExtension('xmlns:xsi', atEppConstants::w3SchemaLocation);

        $contactExt_ = $request->createElement('at-ext-contact:update');
        $contactExt_->setAttribute('xmlns:at-ext-contact', atEppConstants::namespaceAtExtContact);
        $contactExt_->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExtContact);

        if ($this->persTypeChanged) {
            $extChange_ = $request->createElement('at-ext-contact:chg');
            $facet_ = $request->createElement('at-ext-contact:type');
            $facet_->appendChild(new \DOMText($this->atEppContact->getPersonType()));
            $extChange_->appendChild($facet_);
            $contactExt_->appendChild($extChange_);
            $extension->appendchild($contactExt_);
        }
        
        if ($this->atEppContact->getVerificationReport()) {   // add validation report to request if set
            $verficiationExt = $request->createElement('at-ext-verification:update');
            $verficiationExt->setAttribute('xmlns:at-ext-verification', atEppConstants::namespaceAtExtVerification);
            $verficiationExt->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExtVerification);
            $this->atEppContact->getVerificationReport()->exportXML($request, $verficiationExt);
            $extension->appendchild($verficiationExt);
        }

        if(!is_null($this->additionalEppExtension))
        {
            $this->additionalEppExtension->setEppRequestExtension($request,$extension);
        }


    }
}