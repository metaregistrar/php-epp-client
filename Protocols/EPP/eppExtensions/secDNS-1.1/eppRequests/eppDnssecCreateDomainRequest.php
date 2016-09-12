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

class eppDnssecCreateDomainRequest extends eppCreateDomainRequest {
    function __construct($createinfo, $forcehostattr = false, $namespacesinroot=null, eppSecdns $secdns = null) {
        parent::__construct($createinfo, $forcehostattr, $namespacesinroot);
        if ($secdns) {
            $this->addSecdns($secdns);
        }
        $this->addSessionId();
    }

    /*
         * @param eppSecdns $secdns
         */
    public function addSecdns($secdns) {
        /* @var eppSecDNS $secdns */
        if (!$this->extension) {
            $this->extension = $this->createElement('extension');
            $this->getCommand()->appendChild($this->extension);
        }
        $seccreate = $this->createElement('secDNS:create');
        $this->setNamespace('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1',$seccreate);
        if ($secdns->getKeytag()) {
            /*
             * Keytag found, assuming client wants to add dnssec data via dsData interface
             * http://tools.ietf.org/search/rfc5910#section-4.1
             */
            $secdsdata = $this->createElement('secDNS:dsData');
            $secdsdata->appendChild($this->createElement('secDNS:keyTag', $secdns->getKeytag()));
            $secdsdata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
            $secdsdata->appendChild($this->createElement('secDNS:digestType', $secdns->getDigestType()));
            $secdsdata->appendChild($this->createElement('secDNS:digest', $secdns->getDigest()));
            if ($secdns->getPubkey()) {
                /*
                 * Pubkey found, adding option key data to the request
                 */
                $seckeydata = $this->createElement('secDNS:keyData');
                $seckeydata->appendChild($this->createElement('secDNS:flags', $secdns->getFlags()));
                $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
                $seckeydata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
                $seckeydata->appendChild($this->createElement('secDNS:pubKey', $secdns->getPubkey()));
                $secdsdata->appendChild($seckeydata);
            }
            $seccreate->appendChild($secdsdata);
        } else {
            /*
             * Keytag not found, assuming client wants to add dnssec data via keyData interface
             * http://tools.ietf.org/search/rfc5910#section-4.2
             */
            $seckeydata = $this->createElement('secDNS:keyData');
            $seckeydata->appendChild($this->createElement('secDNS:flags', $secdns->getFlags()));
            $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
            $seckeydata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
            $seckeydata->appendChild($this->createElement('secDNS:pubKey', $secdns->getPubkey()));
            $seccreate->appendChild($seckeydata);
        }
        $this->extension->appendChild($seccreate);

        // Put session id at the end of the EPP command chain
        $this->addSessionId();
    }

}