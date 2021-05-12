<?php
namespace Metaregistrar\EPP;

use \DOMElement;

class teleinfoEppCheckNameResponse extends eppResponse {

    /**
     *
     * @return array of checked domains with status true/false
     */
    public function getCheckedNames() {
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $result = array();
            $xpath = $this->xPath();
            $domains = $xpath->query('/epp:epp/epp:response/epp:resData/nv:chkData/nv:cd');
            foreach ($domains as $domain) {
                $childs = $domain->childNodes;
                $checkedname = array('name' => null, 'available' => false, 'restricted'=> false, 'reason' => null);
                foreach ($childs as $child) {
                    if ($child instanceof DOMElement) {
                        if ($child->localName=='name') {
                            $available = $child->getAttribute('avail');
                            switch ($available) {
                                case '0':
                                case 'false':
                                    $checkedname['available'] = false;
                                    break;
                                case '1':
                                case 'true':
                                    $checkedname['available'] = true;
                                    break;
                            }
                            $restricted = $child->getAttribute('restricted');
                            switch($restricted){
                                case '0':
                                case 'false':
                                    $checkedname['restricted'] = false;
                                    break;
                                case '1':
                                case 'true':
                                    $checkedname['restricted'] = true;
                                    break;
                            }
                            $checkedname['name'] = $child->nodeValue;
                        }
                        if ($child->localName=='reason') {
                            $checkedname['reason'] = $child->nodeValue;
                        }
                    }
                }
                $result[] = $checkedname;
            }
        }
        return ($result);
    }
}

