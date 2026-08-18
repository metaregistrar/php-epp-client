<?php

namespace Metaregistrar\EPP;

class itEppInfoDomainResponse extends eppInfoDomainResponse
{

  /**
   * Get domain statuses
   * Merge default statuses with it-extdom-2.0 statuses and rgp
   *
   * @return array
   */
  public function getDomainStatuses()
  {
    // Default statuses.
    $statuses = parent::getDomainStatuses();

    // it-extdom-2.0 statuses.
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

    // Rgp statuses.
    $xpath = $this->xPath();
    $result = $xpath->query('/epp:epp/epp:response/epp:extension/rgp:infData/rgp:rgpStatus');

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

  public function getDomainPendingNameservers()
  {
    $ns = null;

    $xpath = $this->xPath();
    $result = $xpath->query('/epp:epp/epp:response/epp:extension/extdom:infNsToValidateData/extdom:nsToValidate/*');

    if ($result->length > 0) {
      $ns = null;
      foreach ($result as $nameserver) {
        /** @var \DOMElement $nameserver  */
        $hostname = $nameserver->getElementsByTagName('hostName')->item(0)->nodeValue;
        $ipaddresses = $nameserver->getElementsByTagName('hostAddr');
        $ips = null;
        foreach ($ipaddresses as $ip) {
          $ips[] = $ip->nodeValue;
        }
        $ns[] = new eppHost($hostname, $ips);
      }
    }

    return $ns;
  }

  /**
   * Get DNSSEC key data
   * We dit it here because and not in dnssec extension because of multi inerhitance issues
   * https://github.com/metaregistrar/php-epp-client/issues/431
   *
   */
  public function getKeydata()
  {
    // Check if dnssec is enabled on this interface
    if ($this->findNamespace('secDNS')) {
      $xpath = $this->xPath();
      $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');

      $keys = array();
      if (count($result) > 0) {
        foreach ($result as $keydata) {
          /**  @var \DOMElement $keydata  */

          $test = $keydata->getElementsByTagName('keyTag');
          if ($test->length > 0) {
            $secdns = new eppSecdns();
            $secdns->setKeytag($keydata->getElementsByTagName('keyTag')->item(0)->nodeValue);
            $secdns->setAlgorithm($keydata->getElementsByTagName('alg')->item(0)->nodeValue);
            $secdns->setDigestType($keydata->getElementsByTagName('digestType')->item(0)->nodeValue);
            $secdns->setDigest($keydata->getElementsByTagName('digest')->item(0)->nodeValue);
            $keys[] = $secdns;
          }
        }
      }
      return $keys;
    }
    return null;
  }

  /**
   * Get DNSSEC pending key data
   * We dit it here because and not in dnssec extension because of multi inerhitance issues
   * https://github.com/metaregistrar/php-epp-client/issues/431
   *
   */
  public function getPendingKeydata()
  {
    // Check if dnssec is enabled on this interface
    if ($this->findNamespace('secDNS')) {
      $xpath = $this->xPath();
      $result = $xpath->query('/epp:epp/epp:response/epp:extension/extsecDNS:infDsOrKeyToValidateData/extsecDNS:dsOrKeysToValidate/*');

      $keys = array();
      if (count($result) > 0) {
        foreach ($result as $keydata) {
          /**  @var \DOMElement $keydata  */

          $test = $keydata->getElementsByTagName('keyTag');
          if ($test->length > 0) {
            $secdns = new eppSecdns();
            $secdns->setKeytag($keydata->getElementsByTagName('keyTag')->item(0)->nodeValue);
            $secdns->setAlgorithm($keydata->getElementsByTagName('alg')->item(0)->nodeValue);
            $secdns->setDigestType($keydata->getElementsByTagName('digestType')->item(0)->nodeValue);
            $secdns->setDigest($keydata->getElementsByTagName('digest')->item(0)->nodeValue);
            $keys[] = $secdns;
          }
        }
      }
      return $keys;
    }
    return null;
  }
}
