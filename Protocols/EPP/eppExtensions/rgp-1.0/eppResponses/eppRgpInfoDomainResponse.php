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

    /**
     * Get the renewal mode from the returned keysys data. Should actually be put under extensions/keysys but this is always the object returned!
     * @return string|null
     */
    public function getRenewalMode() : ?string
    {
        $xpath = $this->xPath();
        $results = $xpath->query('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:infData/keysys:renewalmode');
        foreach($results as $result)
        {
            return $result->nodeValue;
        }
    }

    /**
     * Get the transfer mode from the returned keysys data. Should actually be put under extensions/keysys but this is always the object returned!
     * @return string|null
     */
    public function getTransferMode() : ?string
    {
        $xpath = $this->xPath();
        $results = $xpath->query('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:infData/keysys:transfermode');
        foreach($results as $result)
        {
            return $result->nodeValue;
        }
    }
}
