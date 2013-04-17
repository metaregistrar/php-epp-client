<?php

#
# rfc5910, rfc6014, rfc4310
# http://www.iana.org/assignments/dns-sec-alg-numbers/dns-sec-alg-numbers.xml
#
/*
 * C:         <secDNS:add>
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

class eppDnssecUpdateRequest extends eppUpdateRequest
{
    function __construct($objectname, eppSecdns $addinfo=null, eppSecdns $reminfo=null)
    {
        parent::__construct($objectname);
        $extension = $this->createElement('extension');
        $secdns = $this->createElement('secDNS:update');
        $secdns->setAttribute('xmlns:secDNS','urn:ietf:params:xml:ns:secDNS-1.1');
        if ($reminfo)
        {
            $rem = $this->createElement('secDNS:rem');
            if (strlen($reminfo->getPubkey())>0)
            {
                $keydata = $this->createElement('secDNS:keyData');
                $keydata->appendChild($this->createElement('secDNS:flags',$reminfo->getFlags()));
                $keydata->appendChild($this->createElement('secDNS:protocol',$reminfo->getProtocol()));
                $keydata->appendChild($this->createElement('secDNS:alg',$reminfo->getAlgorithm()));
                $keydata->appendChild($this->createElement('secDNS:pubKey',$reminfo->getPubkey()));
                $rem->appendChild($keydata);  
            }
            if (strlen($reminfo->getKeytag())>0)
            {
                $dsdata = $this->createElement('secDNS:dsData');
                $dsdata->appendChild($this->createElement('secDNS:keyTag',$reminfo->getKeytag()));
                $dsdata->appendChild($this->createElement('secDNS:alg',$reminfo->getAlgorithm()));
                if (strlen($reminfo->getSiglife())>0)
                {
                    $dsdata->appendChild($this->createElement('secDNS:maxSigLife',$reminfo->getSiglife()));
                }
                $dsdata->appendChild($this->createElement('secDNS:digestType',$reminfo->getDigestType()));
                $dsdata->appendChild($this->createElement('secDNS:digest',$reminfo->getDigest()));
                $rem->appendChild($dsdata);                
            }
            $secdns->appendChild($rem);
        }    
        if ($addinfo)
        {
            $add = $this->createElement('secDNS:add');
            if (strlen($addinfo->getPubkey())>0)
            {
                $keydata = $this->createElement('secDNS:keyData');
                $keydata->appendChild($this->createElement('secDNS:flags',$addinfo->getFlags()));
                $keydata->appendChild($this->createElement('secDNS:protocol',$addinfo->getProtocol()));
                $keydata->appendChild($this->createElement('secDNS:alg',$addinfo->getAlgorithm()));
                $keydata->appendChild($this->createElement('secDNS:pubKey',$addinfo->getPubkey()));
                $add->appendChild($keydata);  
            }
            if (strlen($addinfo->getKeytag())>0)
            {
                $dsdata = $this->createElement('secDNS:dsData');
                $dsdata->appendChild($this->createElement('secDNS:keyTag',$addinfo->getKeytag()));
                $dsdata->appendChild($this->createElement('secDNS:alg',$addinfo->getAlgorithm()));
                if (strlen($addinfo->getSiglife())>0)
                {
                    $dsdata->appendChild($this->createElement('secDNS:maxSigLife',$addinfo->getSiglife()));
                }
                $dsdata->appendChild($this->createElement('secDNS:digestType',$addinfo->getDigestType()));
                $dsdata->appendChild($this->createElement('secDNS:digest',$addinfo->getDigest()));
                $add->appendChild($dsdata);                
            }
            $secdns->appendChild($add);
        }
            
        $extension->appendchild($secdns);
        $this->command->appendChild($extension);
        $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
}
