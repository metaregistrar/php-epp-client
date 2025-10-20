<?php
namespace Metaregistrar\EPP;

class itEppInfoDomainResponse extends eppInfoDomainResponse
{
  public function getDomainStatuses()
  {
    $statuses = parent::getDomainStatuses();

    $xpath = $this->xPath();
    $result = $xpath->query('/epp:epp/epp:response/epp:extension/extdom:infData/extdom:ownStatus');

    foreach ($result as $status) {
      /** @var \DOMElement $status */
      $statuses[] = new eppStatus(
        $status->getAttribute('s'),
        $status->getAttribute('lang'),
        $status->nodeValue
      );
    }

    return $statuses;
  }
}
