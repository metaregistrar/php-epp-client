<?php

namespace Metaregistrar\EPP;

class sidnEppInfoContactResponse extends eppInfoContactResponse
{
    public function getLegalForm()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:contact/sidn-ext-epp:legalForm');
    }
}
