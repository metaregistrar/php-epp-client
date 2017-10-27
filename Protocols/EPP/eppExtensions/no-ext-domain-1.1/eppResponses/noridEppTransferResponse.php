<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dtra for example request/response

class noridEppTransferResponse extends eppTransferResponse {
    
    use noridEppResponseTrait;
    
}