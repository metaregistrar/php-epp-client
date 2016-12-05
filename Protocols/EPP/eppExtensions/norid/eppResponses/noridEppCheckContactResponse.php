<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=cchk for example request/response

class noridEppCheckContactResponse extends eppCheckContactResponse {
    
    public function getExtConditions() {
        return noridEppResponse::getExtConditions($this->xPath());
    }
    
    public function getExtServiceMessages() {
        return noridEppResponse::getExtServiceMessages($this->xPath());
    }
    
}