<?php

namespace Metaregistrar\EPP;

class itEppPollResponse extends eppPollResponse
{

  /**
   * Retrieve the domain name in this poll message.
   *
   * @return null|string
   */
  public function getDomainName()
  {
    $domainName = parent::getDomainName();

    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:extension/extdom:chgStatusMsgData/extdom:name');
    }

    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:extension/extdom:simpleMsgData/extdom:name');
    }

    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:extension/extdom:dnsWarningMsgData/extdom:chgStatusMsgData/extdom:name');
    }

    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:extension/extdom:dnsErrorMsgData/extdom:domain');
    }

    return $domainName;
  }

  /**
   * Get domain statuses from poll message.
   *
   * @return array
   */
  public function getDomainStatuses()
  {
    $statuses[] = parent::getDomainStatus();

    $xpath = $this->xPath();
    $result = $xpath->query('/epp:epp/epp:response/epp:extension/extdom:chgStatusMsgData/extdom:targetStatus/*');

    foreach ($result as $status) {
      /** @var \DOMElement $status */
      $statuses[] = $status->getAttribute('s');
    }

    $statuses = array_filter($statuses);

    return $statuses;
  }
}
