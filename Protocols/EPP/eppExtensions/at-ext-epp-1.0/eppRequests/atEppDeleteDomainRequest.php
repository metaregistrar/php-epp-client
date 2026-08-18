<?php
namespace Metaregistrar\EPP;

/*
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
    <command>
        <delete>
            <domain:delete xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xsi:schemaLocation="urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd">
                <domain:name>example.at</domain:name>
            </domain:delete>
        </delete>
        <extension>
            <at-ext-domain:delete xmlns:at-ext-domain="http://www.nic.at/xsd/at-ext-domain-1.0" xsi:schemaLocation="http://www.nic.at/xsd/at-ext-domain-1.0 at-ext-domain-1.0.xsd">
                <at-ext-domain:scheduledate>expiration</at-ext-domain:scheduledate>
            </at-ext-domain:delete>
        </extension>
        <clTRID>ABC-12345</clTRID>
    </command>
</epp>
*/

class atEppDeleteDomainRequest extends eppDeleteDomainRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;
    const SCHEDULE_DELETE_NOW = "now";
    const SCHEDULE_DELETE_EXPIRATION = "expiration";

    function __construct(eppDomain $deleteinfo, $namespacesinroot = true, $scheduledate = self::SCHEDULE_DELETE_NOW, ?atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($deleteinfo, $namespacesinroot);
        $this->addATScheduledateExtension($scheduledate);
        $this->setAtExtensions();
        $this->addSessionId();
    }

    public function addATScheduledateExtension($scheduleType) {
        $deleteext = $this->createElement('at-ext-domain:delete');
        $deleteext->setAttribute('xmlns:at-ext-domain', 'http://www.nic.at/xsd/at-ext-domain-1.0');
        $scheduleext = $this->createElement('at-ext-domain:scheduledate', $scheduleType);
        $deleteext->appendChild($scheduleext);
        $this->getExtension()->appendChild($deleteext);
    }
}