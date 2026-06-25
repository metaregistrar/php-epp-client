<?php

namespace Metaregistrar\EPP;

use DOMElement;

/*
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:eurid="http://www.eurid.eu/xml/epp/eurid-1.0">
    <command>
        <update>
            <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                <domain:name>somedomain</domain:name>
                <domain:add>
                    <domain:ns>
                        <domain:hostAttr>
                            <domain:hostName>ns1.abc.eu</domain:hostName>
                        </domain:hostAttr>
                        <domain:hostAttr>
                            <domain:hostName>ns2.somedomain.eu</domain:hostName>
                            <domain:hostAddr ip="v4">178.32.172.146</domain:hostAddr>
                        </domain:hostAttr>
                        <domain:hostAttr>
                            <domain:hostName>ns3.somedomain.eu</domain:hostName>
                            <domain:hostAddr ip="v6">2001:67c:9c:1::184</domain:hostAddr>
                        </domain:hostAttr>
                    </domain:ns>
                    <domain:contact type="tech">c320</domain:contact>
                    <domain:contact type="tech">c321</domain:contact>
                </domain:add>
            </domain:update>
        </update>
        <extension>
            <domain-ext:update xmlns:domain-ext="http://www.eurid.eu/xml/epp/domain-ext-2.5">
                <domain-ext:add>
                    <domain-ext:nsgroup>nsgroup-1573042729356</domain-ext:nsgroup>
                </domain-ext:add>
            </domain-ext:update>
        </extension>
        <clTRID>domain-update02</clTRID>
    </command>
</epp>
*/
class euridEppUpdateDomainRequest extends eppUpdateDomainRequest
{
    public function __construct(
        $objectname,
        ?eppDomain $addinfo = null,
        ?eppDomain $removeinfo = null,
        ?eppDomain $updateinfo = null,
        bool $forcehostattr = true,
        bool $namespacesinroot = true,
        bool $usecdata = true,
        string|array|null $addnsgroup = null,
        string|array|null $removensgroup = null
    ) {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot, $usecdata);
        $this->updateNameserverGroups($addnsgroup, $removensgroup);
        parent::addSessionId();
    }

    private function updateNameserverGroups(string|array|null $addnsgroup = null, string|array|null $removensgroup = null): void
    {
        if ($addnsgroup === null && $removensgroup === null) {
            return;
        }

        $update = $this->createElement('domain-ext:update');
        $update->setAttribute('xmlns:domain-ext', 'http://www.eurid.eu/xml/epp/domain-ext-2.5');

        if ($addnsgroup !== null) {
            $this->processNameservergroup($update, 'domain-ext:add', $addnsgroup);
        }

        if ($removensgroup !== null) {
            $this->processNameservergroup($update, 'domain-ext:rem', $removensgroup);
        }

        $this->getExtension()->appendChild($update);
    }

    private function processNameservergroup(DOMElement $parent, string $action, string|array $nsgroup): void
    {
        if (is_array($nsgroup) && !empty($nsgroup)) {
            $element = $this->createElement($action);

            foreach ($nsgroup as $nsgroupname) {
                $element->appendChild($this->createElement('domain-ext:nsgroup', $nsgroupname));
            }

            $parent->appendChild($element);

            return;
        }

        if (is_string($nsgroup)) {
            $element = $this->createElement($action);
            $element->appendChild($this->createElement('domain-ext:nsgroup', $nsgroup));
            $parent->appendChild($element);

            return;
        }


        throw new eppException("The data needed to update the nameserver group must either be an array or a string");
    }
}
