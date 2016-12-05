<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=cupd for example request/response

class noridEppUpdateContactResponse extends eppUpdateContactResponse {
    
    public function getExtConditions() {
        return noridEppResponse::getExtConditions($this->xPath());
    }
    
    public function getExtServiceMessages() {
        return noridEppResponse::getExtServiceMessages($this->xPath());
    }
    
}