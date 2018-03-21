<?php
namespace Metaregistrar\EPP;


class apEppUpdateContactExtension extends atEppExtensionChain
{
    protected $atEppContact=null;

    function __construct(atEppContact $atEppContact, atEppExtensionChain $additionaEppExtension=null) {
        if(!is_null($additionaEppExtension)) {
            parent::__construct($additionaEppExtension);
        }
        $this->atEppContact = $atEppContact;
    }


    public function setEppRequestExtension(eppRequest $request,\DOMElement $extension)
    {
        $request->addExtension('xmlns:xsi', atEppConstants::w3SchemaLocation);

        $contactExt_ = $request->createElement('at-ext-contact:update');
        $contactExt_->setAttribute('xmlns:at-ext-contact', atEppConstants::namespaceAtExtContact);
        $contactExt_->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExtContact);

        $extChange_ = $request->createElement('at-ext-contact:chg');
        $facet_ = $request->createElement('at-ext-contact:type');
        $facet_->appendChild(new \DOMText($this->atEppContact->getPersonType()));
        $extChange_->appendChild($facet_);
        $contactExt_->appendChild($extChange_);
        $extension->appendchild($contactExt_);
        if(!is_null($this->additionaEppExtension))
        {
            $this->additionaEppExtension->setEppRequestExtension($request,$extension);
        }


    }
}