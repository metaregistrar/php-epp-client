<?php
namespace Metaregistrar\EPP;


class atEppCreateContactExtension extends atEppExtensionChain
{
    protected $atEppContact=null;

    function __construct(atEppContact $atEppContact, ?atEppExtensionChain $additionalEppExtension=null) {
        if(!is_null($additionalEppExtension)) {
            parent::__construct($additionalEppExtension);
        }
        $this->atEppContact = $atEppContact;
    }


    public function setEppRequestExtension(eppRequest $request,\DOMElement $extension)
    {
        $request->addExtension('xmlns:xsi', atEppConstants::w3SchemaLocation);

        $contactExt_ = $request->createElement('at-ext-contact:create');
        $contactExt_->setAttribute('xmlns:at-ext-contact', atEppConstants::namespaceAtExtContact);
        $contactExt_->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExtContact);
        $facet_ = $request->createElement('at-ext-contact:type');
        $facet_->appendChild(new \DOMText($this->atEppContact->getPersonType()));
        $contactExt_->appendChild($facet_);
        $extension->appendchild($contactExt_);
        if(!is_null($this->additionalEppExtension))
        {
            $this->additionalEppExtension->setEppRequestExtension($request,$extension);
        }


    }
}