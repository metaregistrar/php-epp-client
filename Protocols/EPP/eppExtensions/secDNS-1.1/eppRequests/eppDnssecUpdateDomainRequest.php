<?php
namespace Metaregistrar\EPP;

#
# rfc5910, rfc6014, rfc4310
# http://www.iana.org/assignments/dns-sec-alg-numbers/dns-sec-alg-numbers.xml
#
/*
C:         <secDNS:add>
C:           <secDNS:dsData>
C:             <secDNS:keyTag>1</secDNS:keyTag>
C:             <secDNS:alg>3</secDNS:alg>
C:             <secDNS:digestType>1</secDNS:digestType>
C:             <secDNS:digest>49FD46E6C4B45C55D4AC</secDNS:digest>
C:             <secDNS:maxSigLife>604800</secDNS:maxSigLife>
C:             <secDNS:keyData>
C:               <secDNS:flags>256</secDNS:flags>
C:               <secDNS:protocol>3</secDNS:protocol>
C:               <secDNS:alg>0</secDNS:alg>
C:               <secDNS:pubKey>AQPJ////4Q==</secDNS:pubKey>
C:             </secDNS:keyData>
C:           </secDNS:dsData>
C:         </secDNS:add>
 */

class eppDnssecUpdateDomainRequest extends eppUpdateDomainRequest {
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null) {
        /* @var $addinfo eppDomain */
        /* @var $removeinfo eppDomain */
        /* @var $updateinfo eppDomain */
        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainname();
        } else {
            $domainname = $objectname;
        }
        if ($updateinfo == null) {
            $updateinfo = new eppDomain($domainname);
        }
        parent::__construct($domainname, $addinfo, $removeinfo, $updateinfo);
        $secdns = $this->createElement('secDNS:update');
        $this->setNamespace('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1',$secdns);
        if ($removeinfo instanceof eppDomain) {
            $dnssecs = $removeinfo->getSecdns();
            foreach ($dnssecs as $dnssec) {
                /* @var $dnssec eppSecdns */
                $rem = $this->createElement('secDNS:rem');
                if (strlen($dnssec->getPubkey()) > 0) {
                    $keydata = $this->createElement('secDNS:keyData');
                    $keydata->appendChild($this->createElement('secDNS:flags', $dnssec->getFlags()));
                    $keydata->appendChild($this->createElement('secDNS:protocol', $dnssec->getProtocol()));
                    $keydata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                    $keydata->appendChild($this->createElement('secDNS:pubKey', $dnssec->getPubkey()));
                    $rem->appendChild($keydata);
                }
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
                $secdns->appendChild($rem);
            }
        }
        if ($addinfo instanceof eppDomain) {
            $dnssecs = $addinfo->getSecdns();
            foreach ($dnssecs as $dnssec) {
                /* @var $dnssec eppSecdns */
                $add = $this->createElement('secDNS:add');
                if (strlen($dnssec->getPubkey()) > 0) {
                    $keydata = $this->createElement('secDNS:keyData');
                    $keydata->appendChild($this->createElement('secDNS:flags', $dnssec->getFlags()));
                    $keydata->appendChild($this->createElement('secDNS:protocol', $dnssec->getProtocol()));
                    $keydata->appendChild($this->createElement('secDNS:alg', $dnssec->getAlgorithm()));
                    $keydata->appendChild($this->createElement('secDNS:pubKey', $dnssec->getPubkey()));
                    $add->appendChild($keydata);
                }
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
                $secdns->appendChild($add);
            }
        }
        $this->getExtension()->appendChild($secdns);
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

}
