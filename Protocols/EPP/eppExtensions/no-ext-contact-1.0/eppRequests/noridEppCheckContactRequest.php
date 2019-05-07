<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=cinf for example request/response

class noridEppCheckContactRequest extends eppCheckContactRequest {

    use noridEppContactRequestTrait;

}
