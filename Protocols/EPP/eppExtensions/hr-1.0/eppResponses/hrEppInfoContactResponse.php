<?php
namespace Metaregistrar\EPP;

class hrEppInfoContactResponse extends eppInfoContactResponse {
    /**
     * Return HR specific contact type which is not EPP standard
     *
     * @return string contact type
     */
    public function getContactType() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/hr:info/hr:contact/hr:type');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
    /**
     * Return HR specific contact identification which is not EPP standard
     *
     * @return string contact identification
     */
    public function getContactIn() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/hr:info/hr:contact/hr:in');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}
