<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hcre for example request/response

class noridEppCreateHostResponse extends eppCreateHostResponse {
    
    public function getExtConditions() {
        return noridEppResponse::getExtConditions($this->xPath());
    }
    
    public function getExtServiceMessages() {
        return noridEppResponse::getExtServiceMessages($this->xPath());
    }
    
}