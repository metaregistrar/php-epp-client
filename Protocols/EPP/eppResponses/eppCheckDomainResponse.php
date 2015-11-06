<?php
namespace Metaregistrar\EPP;

class eppCheckDomainResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    /**
     *
     * @return array of checked domains with status true/false
     */
    public function getCheckedDomains() {
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $result = array();
            $xpath = $this->xPath();
            $domains = $xpath->query('/epp:epp/epp:response/epp:resData/domain:chkData/domain:cd');
            foreach ($domains as $domain) {
                $childs = $domain->childNodes;
                $checkeddomain = array('domainname' => null, 'available' => false, 'reason' => null);
                foreach ($childs as $child) {
                    if ($child instanceof \domElement) {
                        if (strpos($child->tagName, ':name')) {
                            $available = $child->getAttribute('avail');
                            switch ($available) {
                                case '0':
                                case 'false':
                                    $checkeddomain['available'] = false;
                                    break;
                                case '1':
                                case 'true':
                                    $checkeddomain['available'] = true;
                                    break;
                            }
                            $checkeddomain['domainname'] = $child->nodeValue;
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

