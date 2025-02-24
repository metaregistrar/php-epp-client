<?php
namespace Metaregistrar\EPP;

class euridEppUndeleteDomainRequest extends eppDeleteDomainRequest {
    function __construct(eppDomain $deleteinfo, $namespacesinroot = true) {
        parent::__construct($deleteinfo, $namespacesinroot);
        $this->addEURIDExtension();
        $this->addSessionId();
    }

    public function addEURIDExtension() {
        $deleteext = $this->createElement('domain-ext:delete');
        $deleteext->setAttribute('xmlns:domain-ext', 'http://www.eurid.eu/xml/epp/domain-ext-2.5');
        $cancelext = $this->createElement('domain-ext:cancel');
        $deleteext->appendChild($cancelext);
        $this->getExtension()->appendChild($deleteext);
    }
}
