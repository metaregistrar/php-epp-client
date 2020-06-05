<?php
namespace Metaregistrar\EPP;

class verisignEppInfoDomainResponse extends eppInfoDomainResponse {
    public function getDomainRgpStatus(){
        if ($this->findNamespace('rgp')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/rgp:infData/rgp:rgpStatus/@s');
            if (is_object($result) && $result->length>0){
                return $result->item(0)->nodeValue;
            }
        }
        return null;
    }
    public function getDomainRgpEndDate(){
        if ($this->findNamespace('rgp')) {
            $result = $this->queryPath('/epp:epp/epp:response/epp:extension/rgp:infData/rgp:rgpStatus');
            if (!empty($result)){
                return str_replace('endDate=', '', $result);
            }
        }
        return null;
    }
}
