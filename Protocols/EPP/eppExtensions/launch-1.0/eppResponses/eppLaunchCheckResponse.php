<?php
namespace Metaregistrar\EPP;
/**
S:<?xml version="1.0" encoding="UTF-8" standalone="no"?>
S:<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
S:  <response>
S:    <result code="1000">
S:     <msg>Command completed successfully</msg>
S:    </result>
S:   <extension>
S:     <launch:chkData
S:      xmlns:launch="urn:ietf:params:xml:ns:launch-1.0">
S:      <launch:phase>claims</launch:phase>
S:      <launch:cd>
S:        <launch:name exists="0">example1.tld</launch:name>
S:      </launch:cd>
S:      <launch:cd>
S:        <launch:name exists="1">example2.tld</launch:name>
S:        <launch:claimKey validatorID="tmch">
S:        2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX4R0000000001
S:        </launch:claimKey>
S:      </launch:cd>
S:      <launch:cd>
S:        <launch:name exists="1">example3.tld</launch:name>
S:        <launch:claimKey validatorID="tmch">
S:        2013041500/2/6/9/rJ1NrDO92vDsAzf7EQzgjX4R0000000001
S:        </launch:claimKey>
S:        <launch:claimKey validatorID="custom-tmch">
S:        20140423200/1/2/3/rJ1Nr2vDsAzasdff7EasdfgjX4R000000002
S:        </launch:claimKey>
S:      </launch:cd>
S:     </launch:chkData>
S:    </extension>
S:    <trID>
S:     <clTRID>ABC-12345</clTRID>
S:     <svTRID>54321-XYZ</svTRID>
S:    </trID>
S:  </response>
S:</epp>
 */

class eppLaunchCheckResponse extends eppCheckResponse {
    public function getLaunchPhase() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/launch:chkData/launch:phase');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getDomainClaims() {
        $idna = new eppIDNA();
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $result = array();
            $xpath = $this->xPath();
            $domains = $xpath->query('/epp:epp/epp:response/epp:extension/launch:chkData/launch:cd');
            foreach ($domains as $domain) {
                $childs = $domain->childNodes;
                $checkeddomain = array('domainname' => null, 'available' => false, 'reason' => null, 'claimed' => false);
                foreach ($childs as $child) {
                    if ($child instanceof \domElement) {
                        if (strpos($child->tagName, ':name')) {
                            $exists = $child->getAttribute('exists');
                            switch ($exists) {
                                case '0':
                                case 'false':
                                    $checkeddomain['claimed'] = false;
                                    break;
                                case '1':
                                case 'true':
                                    $checkeddomain['claimed'] = true;

                                    break;
                            }
                        }
                        if (strpos($child->tagName, ':claimKey')) {
                            $checkeddomain['claim'] = new eppDomainClaim();
                            $checkeddomain['claim']->setValidator($child->getAttribute('validatorID'));
                            $checkeddomain['claim']->setClaimKey($child->nodeValue);
                        }
                        if (strpos($child->tagName, ':name')) {
                            $checkeddomain['domainname'] = $idna->decode($child->nodeValue);
                        }
                        if (strpos($child->tagName, ':reason')) {
                            $checkeddomain['reason'] = $child->nodeValue;
                        }
                    }
                }
                $result[] = $checkeddomain;
            }
        }
        return ($result);
    }
}