<?php

namespace Metaregistrar\EPP;

use DOMElement;

class euridEppUpdateDomainRequest extends eppUpdateDomainRequest
{
    public function __construct(
        $objectname,
        ?eppDomain $addinfo = null,
        ?eppDomain $removeinfo = null,
        ?eppDomain $updateinfo = null,
        bool $forcehostattr = true,
        bool $namespacesinroot = true,
        string|array|null $addnsgroup = null,
        string|array|null $removensgroup = null
    ) {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot);
        $this->updatensgroup($addnsgroup, $removensgroup);
        parent::addSessionId();
    }

    public function updatensgroup(string|array|null $addnsgroup = null, string|array|null $removensgroup = null): void
    {
        if ($addnsgroup === null && $removensgroup === null) {
            return;
        }

        $create = $this->createElement('domain-ext:update');
        $this->setNamespace('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0', $create);
        $this->setNamespace('xmlns:domain-ext', 'http://www.eurid.eu/xml/epp/domain-ext-2.5', $create);

        if ($addnsgroup !== null) {
            $this->processNsgroup($create, 'domain-ext:add', $addnsgroup);
        }

        if ($removensgroup !== null) {
            $this->processNsgroup($create, 'domain-ext:rem', $removensgroup);
        }

        $this->getExtension()->appendChild($create);
    }

    private function processNsgroup(DOMElement $parent, string $action, string|array $nsgroup): void
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
