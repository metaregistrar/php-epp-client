<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hdel for example request/response

class noridEppInfoContactResponse extends eppInfoContactResponse {
    
    use noridEppResponseTrait;

    public function getExtType() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/no-ext-contact:infData/no-ext-contact:type');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getExtOrganization() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/no-ext-contact:infData/no-ext-contact:organization');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getExtIdentity() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/no-ext-contact:infData/no-ext-contact:identity');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getExtMobilePhone() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/no-ext-contact:infData/no-ext-contact:mobilePhone');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getExtEmails() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/no-ext-contact:infData/no-ext-contact:email');
        if (is_object($result) && ($result->length > 0)) {
            return array_map(function ($element) {
                return $element->nodeValue;
            }, $result);
        } else {
            return null;
        }
    }

    public function getExtRoleContacts() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/no-ext-contact:infData/no-ext-contact:roleContact');
        if (is_object($result) && ($result->length > 0)) {
            return array_map(function ($element) {
                return $element->nodeValue;
            }, $result);
        } else {
            return null;
        }
    }
    
}