<?php
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 01.12.2015
 * Time: 15:59
 */

namespace Metaregistrar\EPP;


class atEppInfoDomainResponse extends eppInfoDomainResponse
{
    public function getKeydata() {
        // Check if dnssec is enabled on this interface

        if ($this->findNamespace('secDNS')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
            $keys = array();

            if (count($result) > 0) {
                foreach ($result as $keydata) {
                    /* @var $keydata \DOMElement */
                    $secdns = new eppSecdns();
                    $secdns->setKeytag($keydata->getElementsByTagName('keyTag')->item(0)->nodeValue);
                    $secdns->setAlgorithm($keydata->getElementsByTagName('alg')->item(0)->nodeValue);
                    $secdns->setDigestType($keydata->getElementsByTagName('digestType')->item(0)->nodeValue);
                    $secdns->setDigest($keydata->getElementsByTagName('digest')->item(0)->nodeValue);
                    $keys[] = $secdns;
                }
            }
            return $keys;
        }
        return null;
    }
}