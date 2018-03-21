<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dcre-ad for example request/response

class noridEppCreateDomainResponse extends eppCreateDomainResponse {
    
    use noridEppResponseTrait;
    
}