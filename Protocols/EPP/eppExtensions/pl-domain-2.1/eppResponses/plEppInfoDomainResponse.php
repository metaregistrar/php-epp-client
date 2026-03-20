<?php

namespace Metaregistrar\EPP;

class plEppInfoDomainResponse extends eppInfoDomainResponse
{
  /**
   * Pl. registry returns nameservers as <domain:ns> elements (old RFC 3735) instead of <domain:hostObj> (RFC 5731) elements,
   * so we need to override the default implementation of getDomainNameservers to read the correct XML structure.
   *
   * @return null|eppHost[]
   */
  public function getDomainNameservers()
  {
    $ns = null;
    $xpath = $this->xPath();
    $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:ns');
    foreach ($result as $host) {
      $ns[] = new eppHost($host->nodeValue);
    }
    return $ns;
  }
}
