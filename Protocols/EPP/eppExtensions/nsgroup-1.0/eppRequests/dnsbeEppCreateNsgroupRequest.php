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
    /**
     * @var \DOMElement
     */
    private $hostobject=null;

    /**
     * dnsbeEppCreateNsgroupRequest constructor.
     * @param $groupname
     * @param $hosts
     * @throws eppException
     */
    function __construct($groupname, $hosts) {
        parent::__construct();
        $this->addExtension('xmlns:nsgroup', 'http://www.dns.be/xml/epp/nsgroup-1.0');
        if (is_string($groupname)) {
            if (strlen($groupname) > 0) {
                $this->addNsGroup($groupname);
                if (is_array($hosts)) {
                    foreach ($hosts as $host) {
                        if ($host instanceof eppHost) {
                            $this->addHost($host);
                        }
                    }
                } else {
                    // If you do not add an array of hosts, but just one
                    if ($hosts instanceof eppHost) {
                        $this->addHost($hosts);
                    }
                }
            } else {
                throw new eppException("Groupname must be a valid name on dnsbeEppCreateNsgroupRequest");
            }
        } else {
            throw new eppException("Groupname must be a string on dnsbeEppCreateNsgroupRequest");
        }


        $this->addSessionId();
    }

    /**
     * @param $groupname
     */
    private function addNsGroup($groupname) {
        $create = $this->createElement('create');
        $this->hostobject = $this->createElement('nsgroup:create');
        $this->hostobject->appendChild($this->createElement('nsgroup:name', $groupname));
        $create->appendChild($this->hostobject);
        $this->getCommand()->appendChild($create);
    }

    /**
     * @param eppHost $host
     * @throws eppException
     */
    private function addHost(eppHost $host) {
        if (!strlen($host->getHostname())) {
            throw new eppException('No valid hostname in create host request');
        }
        if (isset($this->hostobject)) {
            $this->hostobject->appendChild($this->createElement('nsgroup:ns', $host->getHostname()));
        }
        return;
    }
}