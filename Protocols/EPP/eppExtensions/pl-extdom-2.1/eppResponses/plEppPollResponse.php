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

    // BROKEN_DELEGATION
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:dlgData/extdom:name');
    }

    // EXPIRATION_POSSIBLE
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:expData/extdom:name');
    }

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

    // DOMAIN_DELEGATION_ON_HOLD
    if ($domainName === null) {
      $domainName =  $this->queryPath('/epp:epp/epp:response/epp:resData/extdom:pollDomainDelegationOnHold/extdom:domain/extdom:name');
    }

    return $domainName;
  }
}
