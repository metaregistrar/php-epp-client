<?php

namespace Metaregistrar\EPP;

class itEppCreateDomainResponse extends eppCreateDomainResponse
{
  public function getIdnRequested()
  {
    return $this->queryPath('/epp:epp/epp:response/epp:extension/extdom:remappedIdnData/extdom:idnRequested');
  }

  public function getIdnCreated()
  {
    return $this->queryPath('/epp:epp/epp:response/epp:extension/extdom:remappedIdnData/extdom:idnCreated');
  }
}
