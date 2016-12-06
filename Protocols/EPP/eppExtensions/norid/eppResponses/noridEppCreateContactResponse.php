<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=ccre for example request/response

class noridEppCreateContactResponse extends eppCreateContactResponse {
    
    use noridEppResponseTrait;
    
}