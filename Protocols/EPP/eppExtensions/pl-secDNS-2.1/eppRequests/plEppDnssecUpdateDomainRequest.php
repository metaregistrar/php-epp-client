<?php

namespace Metaregistrar\EPP;

class plEppDnssecUpdateDomainRequest extends plEppUpdateDomainRequest
{
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr = false, $namespacesinroot = true)
    {
        /** @var eppDomain $addinfo */
        /** @var eppDomain $removeinfo */
        /** @var eppDomain $updateinfo */
        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainName();
        } else {
            $domainname = $objectname;
        }
        if ($updateinfo == null) {
            $updateinfo = new eppDomain($domainname);
        }
        parent::__construct($domainname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot);
        $secdns = $this->createElement('secDNS:update');
        $secdns->setAttribute('xmlns:secDNS', 'http://www.dns.pl/nask-epp-schema/secDNS-2.1');
        $secdns_updated = false;
        if ($removeinfo instanceof eppDomain) {
            $dnssecs = $removeinfo->getSecdns();
            if (count($dnssecs) > 0) {
                $rem = $this->createElement('secDNS:rem');
                foreach ($dnssecs as $dnssec) {
                    /** @var eppSecdns $dnssec */
                    if (strlen($dnssec->getKeytag()) > 0) {
                        $dsdata = $this->createElement('secDNS:dsData');
                        $dsdata->appendChild($this->createElement('secDNS:keyTag', $dnssec->getKeytag()));
                        $dsdata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                        if (strlen($dnssec->getSiglife()) > 0) {
                            $dsdata->appendChild($this->createElement('secDNS:maxSigLife', $dnssec->getSiglife()));
                        }
                        $dsdata->appendChild($this->createElement('secDNS:digestType', $dnssec->getDigestType()));
                        $dsdata->appendChild($this->createElement('secDNS:digest', $dnssec->getDigest()));
                        $rem->appendChild($dsdata);
                    }
                    if (strlen($dnssec->getPubkey()) > 0) {
                        $keydata = $this->createElement('secDNS:keyData');
                        $keydata->appendChild($this->createElement('secDNS:flags', $dnssec->getFlags()));
                        $keydata->appendChild($this->createElement('secDNS:protocol', $dnssec->getProtocol()));
                        $keydata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                        $keydata->appendChild($this->createElement('secDNS:pubKey', $dnssec->getPubkey()));
                        $rem->appendChild($keydata);
                    }
                }
                $secdns->appendChild($rem);
                $secdns_updated = true;
            }
        }
        if ($addinfo instanceof eppDomain) {
            $dnssecs = $addinfo->getSecdns();
            if (count($dnssecs) > 0) {
                $add = $this->createElement('secDNS:add');
                foreach ($dnssecs as $dnssec) {
                    /** @var eppSecdns $dnssec */
                    if (strlen($dnssec->getKeytag()) > 0) {
                        $dsdata = $this->createElement('secDNS:dsData');
                        $dsdata->appendChild($this->createElement('secDNS:keyTag', $dnssec->getKeytag()));
                        $dsdata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                        if (strlen($dnssec->getSiglife()) > 0) {
                            $dsdata->appendChild($this->createElement('secDNS:maxSigLife', $dnssec->getSiglife()));
                        }
                        $dsdata->appendChild($this->createElement('secDNS:digestType', $dnssec->getDigestType()));
                        $dsdata->appendChild($this->createElement('secDNS:digest', $dnssec->getDigest()));
                        $add->appendChild($dsdata);
                    }
                    if (strlen($dnssec->getPubkey()) > 0) {
                        $keydata = $this->createElement('secDNS:keyData');
                        $keydata->appendChild($this->createElement('secDNS:flags', $dnssec->getFlags()));
                        $keydata->appendChild($this->createElement('secDNS:protocol', $dnssec->getProtocol()));
                        $keydata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                        $keydata->appendChild($this->createElement('secDNS:pubKey', $dnssec->getPubkey()));
                        $add->appendChild($keydata);
                    }
                }
                $secdns->appendChild($add);
                $secdns_updated = true;
            }
        }
        if ($secdns_updated) {
            $this->getExtension()->appendchild($secdns);
        }
        $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }
}
