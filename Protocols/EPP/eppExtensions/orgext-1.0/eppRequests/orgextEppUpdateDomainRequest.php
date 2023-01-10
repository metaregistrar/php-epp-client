<?php
namespace Metaregistrar\EPP;

// See https://docs.dnsbelgium.be/be/epp/updatedomain.html for example request/response

class orgextEppUpdateDomainRequest extends eppUpdateDomainRequest {

    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false, $namespacesinroot=true, $usecdata = true) {
        parent::__construct($objectname,$addinfo,$removeinfo,$updateinfo,$forcehostattr,$namespacesinroot,$usecdata);
        $this->addSessionId();
    }
    /**
    <extension>
    <orgext:update xmlns:orgext="urn:ietf:params:xml:ns:epp:orgext-1.0">
    <orgext:rem>
    <orgext:id role="reseller"/>
    </orgext:rem>
    </orgext:update>
    </extension>
    </command>
    </epp>
     */
    public function removeReseller() {
        $this->addExtension('xmlns:orgext', 'urn:ietf:params:xml:ns:epp:orgext-1.0');
        $element = $this->createElement('orgext:update');
        $remove = $this->createElement('orgext:rem');
        $id = $this->createElement('orgext:id');
        $id->setAttribute('role','reseller');
        $remove->appendChild($id);
        $element->appendChild($remove);
        $this->getExtension()->appendChild($element);
        $this->addSessionId();
    }

}
