<?php

namespace Metaregistrar\EPP;

/**
 * Class eppRgpInfoDomainResponse
 */
class eppRgpInfoDomainResponse extends eppInfoDomainResponse
{

  public function getRgpStatuses()
  {
    $statuses = null;
    $xpath = $this->xPath();
    $result = $xpath->query('/epp:epp/epp:response/epp:extension/rgp:infData/rgp:rgpStatus/@s');
    foreach ($result as $status) {
      $statuses[] = $status->nodeValue;
    }
    return $statuses;
  }
}
