<?php

namespace Metaregistrar\EPP;

class sidnEppInfoContactResponse extends eppInfoContactResponse
{
    public function getLegalForm()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:contact/sidn-ext-epp:legalForm');
        return $result->item(0)->nodeValue;
    }
}
