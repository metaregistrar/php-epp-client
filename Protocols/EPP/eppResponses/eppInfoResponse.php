<?php
namespace Metaregistrar\EPP;

class eppInfoResponse extends eppResponse {
    /**
     *
     * @param array $arr
     * @return string
     */
    protected function arrayToCSV($arr) {
        $ret = '';
        if (is_array($arr)) {
            foreach ($arr as $value) {
                if (is_string($value)) {
                    if (strlen($ret)) {
                        $ret .= ',';
                    }
                    $ret .= $value;
                } else {
                    if ($value instanceof eppStatus) {
                        if (strlen($ret)) {
                            $ret .= ',';
                        }
                        $ret .= $value->getStatusname();
                    }
                }
            }
        }
        return $ret;
    }


}
