<?php

namespace Metaregistrar\EPP;

class bNamedEppCreateDomainRequest extends eppCreateDomainRequest {

 
    function __construct($createinfo, $bNamedExtraInfo = null, $forcehostattr = false, $namespacesinroot = true) {
        parent::__construct($createinfo, $forcehostattr, $namespacesinroot);
        $this-> addbNamedExtension($bNamedExtraInfo);
        $this->addSessionId();
    }

    private function addbNamedExtension(array $bNamedExtraInfo) {
        if (is_null($bNamedExtraInfo)) {
        } else {
            $ext = $this->createElement('extension');
            $bnameddomaincreate = $this->createElement('bnamed-domain:create');
            $bnameddomaincreate->setAttribute('xmlns:bnamed-domain', 'http://www.bNamed.net/xsd/bnamed-1.0');
            $bnameddomaincreate->setAttribute('xmlns:bnamed', 'http://www.bNamed.net/xsd/bnamed-1.0');            
            //go through $bNamedExtraInfo
            foreach ($bNamedExtraInfo as $key => $value) {
                $bnamedfield = $this->createElement('bnamed:field');
                $key = $this->createElement('key',$key);    
                $value = $this->createElement('value',$value);
                $bnamedfield ->appendChild($key);
                $bnamedfield ->appendChild($value);
                $bnameddomaincreate->appendChild($bnamedfield);
            }            
            $ext -> appendChild($bnameddomaincreate);            
            $this->getCommand()->appendChild($ext);
        }
    }

}
?>
