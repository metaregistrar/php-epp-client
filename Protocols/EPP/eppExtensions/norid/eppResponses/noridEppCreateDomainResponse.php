<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dcre-ad for example request/response

class noridEppCreateDomainResponse extends eppCreateDomainResponse {
    
    public function getExtConditions() {
        return noridEppResponse::getExtConditions($this->xPath());
    }
    
    public function getExtServiceMessages() {
        return noridEppResponse::getExtServiceMessages($this->xPath());
    }
    
}