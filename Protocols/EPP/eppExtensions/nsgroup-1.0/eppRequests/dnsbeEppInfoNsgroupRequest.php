<?php
namespace Metaregistrar\EPP;
/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
    <command>
        <info>
            <nsgroup:info>
                <nsgroup:name>test</nsgroup:name>
            </nsgroup:info>
        </info>
        <clTRID>nsgroup-test-456</clTRID>
    </command>
</epp>

*/
class dnsbeEppInfoNsgroupRequest extends eppRequest {
    /**
     * @var \DOMElement
     */
    private $hostobject;

    /**
     * dnsbeEppInfoNsgroupRequest constructor.
     * @param $nsgroup
     * @throws eppException
     */
    function __construct($nsgroup)
    {
        parent::__construct();
        $this->addExtension('xmlns:nsgroup', 'http://www.dns.be/xml/epp/nsgroup-1.0');
        if (is_string($nsgroup)) {
            if (strlen($nsgroup) > 0) {
                $this->addNsGroup($nsgroup);
            } else {
                throw new eppException("Domain name length may not be 0 on eppAuthcodeRequest");
            }
        } else {
            throw new eppException("Domain name must be string on eppAuthcodeRequest");
        }
        $this->addSessionId();
    }

    /**
     * @param $groupname
     */
    private function addNsGroup($groupname) {
        $create = $this->createElement('info');
        $this->hostobject = $this->createElement('nsgroup:info');
        $this->hostobject->appendChild($this->createElement('nsgroup:name', $groupname));
        $create->appendChild($this->hostobject);
        $this->getCommand()->appendChild($create);
    }
}