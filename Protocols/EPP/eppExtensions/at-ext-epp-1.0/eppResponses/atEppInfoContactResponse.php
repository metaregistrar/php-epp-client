<?php
namespace Metaregistrar\EPP;


class atEppInfoContactResponse extends eppInfoContactResponse
{

    public function getWhoisHidePhone()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:disclose/contact:voice');

        if (!is_null($result) &&$result->length > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getWhoisHideFax()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:disclose/contact:fax');

        if (!is_null($result) &&$result->length > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getWhoisHideEmail()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:disclose/contact:email');

        if (!is_null($result) && $result->length > 0) {
            return 1;
        } else {
            return 0;
        }
    }


    public function getPersonType()
    {

        $xpath = $this->xPath();
       $xpath->registerNamespace ( "at-ext-contact" , atEppConstants::namespaceAtExtContact );

        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext-contact:infData/at-ext-contact:type');
        if (!is_null($result) && $result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return atEppContact::PERS_TYPE_UNSPECIFIED;
        }
    }



}