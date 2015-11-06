<?php
namespace Metaregistrar\EPP;

class eppCheckHostResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    /**
     *
     * @return array of checked hosts with status true/false
     */
    public function getCheckedHosts() {
        $avail = null;
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $xpath = $this->xPath();
            $hosts = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:name');
            $checks = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:name/@avail');
            foreach ($hosts as $idx => $host) {
                $available = $checks->item($idx)->nodeValue;
                switch ($available) {
                    case '0':
                    case 'false':
                        $avail = false;
                        break;
                    case '1':
                    case 'true':
                        $avail = true;
                        break;
                }
                $result[$host->nodeValue] = $avail;
            }
        }
        return ($result);
    }

}

