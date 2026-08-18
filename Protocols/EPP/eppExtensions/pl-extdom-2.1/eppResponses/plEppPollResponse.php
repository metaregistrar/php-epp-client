<?php

namespace Metaregistrar\EPP;

class plEppPollResponse extends eppPollResponse
{
  /**
   * Retrieve the domain name in this poll message.
   *
   * @return null|string
   */
  public function getDomainName()
  {
    $domainName = parent::getDomainName();

    // DOMAIN_AUTHINFO
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollAuthInfo/extdom:domain/extdom:name');
    }

    // DOMAIN_BLOCKED
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainBlocked/extdom:domain/extdom:name');
    }

    // DOMAIN_UNBLOCKED
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainUnblocked/extdom:domain/extdom:name');
    }

    // DOMAIN_JUDICIAL_REMOVED
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainJudicialRemoved/extdom:domain/extdom:name');
    }

    // DOMAIN_AUTO_RENEWED
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainAutoRenewed/extdom:name');
    }

    // DOMAIN_AUTO_RENEW_FAILED
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainAutoRenewFailed/extdom:name');
    }

    // PREPAID_DOMAIN_LOCK
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainLocked/extdom:domain/extdom:name');
    }

    // PREPAID_DOMAIN_UNLOCK
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainUnlocked/extdom:domain/extdom:name');
    }

    return $domainName;
  }

  /**
   * Get domain names in this poll message, for some message types there can be multiple domains in one message.
   *
   * @return array|null
   */
  public function getDomainNames()
  {
    $domainNames = null;

    // BROKEN_DELEGATION
    if ($domainNames === null) {
      $xpath = $this->xPath();
      $result = $xpath->query('/epp:epp/epp:response/epp:resData/extdom:dlgData/extdom:name');
      foreach ($result as $domain) {
        $domainNames[] = $domain->nodeValue;
      }
    }

    // EXPIRATION_POSSIBLE
    if ($domainNames === null) {
      $xpath = $this->xPath();
      $result = $xpath->query('/epp:epp/epp:response/epp:resData/extdom:expData/extdom:name');
      foreach ($result as $domain) {
        $domainNames[] = $domain->nodeValue;
      }
    }

    // DOMAIN_DELEGATION_ON_HOLD
    if ($domainNames === null) {
      $xpath = $this->xPath();
      $result = $xpath->query('/epp:epp/epp:response/epp:resData/extdom:pollDomainDelegationOnHold/extdom:domain/extdom:name');
      foreach ($result as $domain) {
        $domainNames[] = $domain->nodeValue;
      }
    }

    return $domainNames;
  }
}
