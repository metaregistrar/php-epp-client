<?php
namespace Metaregistrar\EPP;

class ficoraEppCheckContactResponse extends eppCheckContactResponse {
    /**
     *
     * @return array of checked contacts with status true/false
     */
    public function getCheckedContacts() {
        $avail = null;
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $xpath = $this->xPath();
            $contacts = $xpath->query('/epp:epp/epp:response/epp:resData/contact:chkData/contact:cd/contact:name');
            $checks = $xpath->query('/epp:epp/epp:response/epp:resData/contact:chkData/contact:cd/contact:name/@avail');
            foreach ($contacts as $idx => $contact) {
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
                $result[$contact->nodeValue] = $avail;
            }
        }
        return ($result);
    }
}