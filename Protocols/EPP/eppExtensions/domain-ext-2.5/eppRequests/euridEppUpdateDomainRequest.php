<?php

namespace Metaregistrar\EPP;

class euridEppUpdateDomainRequest extends eppUpdateDomainRequest
{
    public function __construct(
        $objectname,
        $addinfo = null,
        $removeinfo = null,
        $updateinfo = null,
        $forcehostattr = true,
        $namespacesinroot = true,
        $addnsgroup = null,
        $removensgroup = null
    ) {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot);
        $this->updatensgroup($addnsgroup, $removensgroup);
    }

    public function updatensgroup($addnsgroup = null, $removensgroup = null)
    {
        if ($addnsgroup === null && $removensgroup === null) {
            return;
        }

        $this->addExtension('xmlns:dnsbe', 'http://www.eurid.eu/xml/epp/domain-ext-2.6');
        $ext = $this->createElement('extension');
        $dnsext = $this->createElement('domain-ext');
        $update = $this->createElement('domain-ext:update');
        $domain = $this->createElement('domain-ext:rem');
        if ($addnsgroup !== null) {
            if (is_array($addnsgroup) && !empty($addnsgroup)) {
                $add = $this->createElement('domain-ext:add');
                foreach ($addnsgroup as $nsgroupname) {
                    $add->appendChild($this->createElement('domain-ext:nsgroup', $nsgroupname));
                }
            } elseif (is_string($addnsgroup)) {
                $add = $this->createElement('domain-ext:add');
                $add->appendChild($this->createElement('domain-ext:nsgroup', $addnsgroup));
            } else {
                throw new eppException("addnsgroup must either be an array or a string in updatensgroup");
            }
            $domain->appendChild($add);
        }
        if ($removensgroup !== null) {
            if (is_array($removensgroup) && !empty($removensgroup)) {
                $rem = $this->createElement('domain-ext:rem');
                foreach ($removensgroup as $nsgroupname) {
                    $rem->appendChild($this->createElement('domain-ext:nsgroup', $nsgroupname));
                }
            } elseif (is_string($removensgroup)) {
                $rem = $this->createElement('domain-ext:rem');
                $rem->appendChild($this->createElement('domain-ext:nsgroup', $removensgroup));
            } else {
                throw new eppException("removensgroup must either be an array or a string in updatensgroup");
            }
            $domain->appendChild($rem);
        }
        $update->appendChild($domain);
        $dnsext->appendChild($update);
        $ext->appendChild($dnsext);
        $this->getCommand()->appendChild($ext);
        $this->addSessionId();
    }

}
