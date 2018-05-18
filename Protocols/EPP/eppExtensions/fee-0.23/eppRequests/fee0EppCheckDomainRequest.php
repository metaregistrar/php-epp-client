<?php
namespace Metaregistrar\EPP;

class fee0EppCheckDomainRequest extends eppCheckDomainRequest {
    function __construct($checkrequest, $namespacesinroot) {
        parent::__construct($checkrequest, $namespacesinroot);
        $this->addFee(null,null);
        $this->addSessionId();
    }

    private function addFee(eppDomain $domain, $command, $phase=null, $period = null) {
        $extension = $this->getExtension();
        $check = $this->createElement('fee:check');
        $check->setAttribute('xmlns:fee','urn:ietf:params:xml:ns:fee-0.9');
        $fee = $this->createElement('fee:object');
        $fee->setAttribute('objURI','urn:ietf:params:xml:ns:domain-1.0');
        $objid = $this->createElement('fee:objID',$domain->getDomainname());
        $objid->setAttribute('element','name');
        $fee->appendChild($objid);
        $command = $this->createElement('fee:command',$command);
        if ($phase) {
            $command->setAttribute('phase',$phase);
            $fee->appendChild($phase);
        }
        if ($period) {
            $per = $this->createElement('fee:period',$period);
            $per->setAttribute('unit','y');
        }
        $extension->appendChild($check);
        return;
    }
}