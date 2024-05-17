<?php
namespace Metaregistrar\EPP;


class atEppUndeleteRequest extends eppDomainRequest
{
    use atEppCommandTrait;

    /**
     * atEppUndeleteRequest constructor.
     * @param eppDomain $domain
     * @param atEppExtensionChain|null $atEppExtensionChain
     */
    function __construct(eppDomain $domain, ?atEppExtensionChain $atEppExtensionChain = null) {
        $this->atEppExtensionChain = $atEppExtensionChain;

        parent::__construct(eppRequest::TYPE_UPDATE);
        $domainname = $domain->getDomainname();
        $this->domainobject->appendChild($this->createElement('domain:name', $domainname));
        $this->setUndeleteExt();
        $this->epp->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->addSessionId();

    }


    /**
     * set's the RGP-Extension
     */
    protected function setUndeleteExt() {

        $updExt = $this->createElement('rgp:update');
        $updExt->setAttribute('xmlns:rgp',"urn:ietf:params:xml:ns:rgp-1.0" );
        $updExt->setAttribute('xsi:schemaLocation', "urn:ietf:params:xml:ns:rgp-1.0 rgp-1.0.xsd");

        $restoreCmd = $this->createElement('rgp:restore');
        $restoreCmd->setAttribute('op', "request");
        $updExt->appendChild($restoreCmd);
        $this->getExtension()->appendChild($updExt);
        $this->setAtExtensions();
    }


}