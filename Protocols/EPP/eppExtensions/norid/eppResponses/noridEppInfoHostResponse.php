<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hinf for example request/response

class noridEppInfoHostResponse extends eppInfoHostResponse {
    
    use noridEppResponseTrait;

    public function getExtContact() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/no-ext-host:infData/no-ext-host:contact');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
    
}