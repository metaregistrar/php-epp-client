<?php
namespace Metaregistrar\EPP;
/*
 *
<epp xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd http://www.dns.be/xml/epp/nsgroup-1.0 nsgroup-1.0.xsd" xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:nsgroup="http://www.dns.be/xml/epp/nsgroup-1.0">
  <command>
    <create>
        <nsgroup:create>
            <nsgroup:name>ns1.nameserver.be</nsgroup:name>
            <nsgroup:ns>ns1.nameserver.be</nsgroup:ns>
        </nsgroup:create>
       </create>
    <clTRID>clientref-00011</clTRID>
  </command>
</epp>
 */
class dnsbeEppCreateNsgroupRequest extends eppRequest {

    private $hostobject;

    function __construct($createinfo) {
        parent::__construct();

        if ($createinfo instanceof eppHost) {
            $this->addExtension('xmlns:nsgroup', 'http://www.dns.be/xml/epp/nsgroup-1.0');
            $this->addNsGroup($createinfo);
        }
        $this->addSessionId();
    }

    private function addNsGroup(eppHost $host) {
        if (!strlen($host->getHostname())) {
            throw new eppException('No valid hostname in create host request');
        }
        #
        # Object create structure
        #
        $create = $this->createElement('create');
        $this->hostobject = $this->createElement('nsgroup:create');
        $this->hostobject->appendChild($this->createElement('nsgroup:name', $host->getHostname()));
        $this->hostobject->appendChild($this->createElement('nsgroup:ns', $host->getHostname()));
        $create->appendChild($this->hostobject);
        $this->getCommand()->appendChild($create);
        return;
    }
}