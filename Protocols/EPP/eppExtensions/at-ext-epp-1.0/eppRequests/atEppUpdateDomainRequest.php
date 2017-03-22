<?php
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 17.09.2015
 * Time: 14:03
 */

namespace Metaregistrar\EPP;


class atEppUpdateDomainRequest extends eppUpdateDomainRequest
{
    use \Metaregistrar\EPP\atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false,atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($objectname, $addinfo , $removeinfo , $updateinfo , $forcehostattr);
        $secdns = $this->createElement('secDNS:update');
        $secdns->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');
        $secdns->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:secDNS-1.1 secDNS-1.1.xsd');
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
            }
            $secdns->appendChild($rem);
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

            }
            $secdns->appendChild($add);
        }
        $this->getExtension()->appendchild($secdns);

        $this->addSessionId();

    }

    public function updateDomain($domainname, $addInfo, $removeInfo, $updateInfo) {
        parent::updateDomain($domainname, $addInfo, $removeInfo, $updateInfo);

        $this->setAtExtensions();
    }
}