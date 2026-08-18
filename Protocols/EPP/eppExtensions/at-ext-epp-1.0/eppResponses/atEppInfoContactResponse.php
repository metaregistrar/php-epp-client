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

    public function getValidationReport()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext-verification" , atEppConstants::namespaceAtExtVerification );

        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext-verification:infData/at-ext-verification:report');
        if (!is_null($result) && $result->length > 0) {
            $verificationReport=new atEppVerificationReport();
            $verificationReport->setReceivedDate($result->item(0)->getAttribute('receivedDate'));
            $verificationReport->setclID($result->item(0)->getAttribute('clID'));
            $verificationReport->setResult($result->item(0)->getElementsByTagName('result')->item(0)->nodeValue);
            $verificationReport->setVerificationDate($result->item(0)->getElementsByTagName('verificationDate')->item(0)->nodeValue);
            $verificationReport->setMethod($result->item(0)->getElementsByTagName('method')->item(0)->nodeValue);
            $verificationReport->setReference($result->item(0)->getElementsByTagName('reference')->item(0)->nodeValue);
            $verificationReport->setAgent($result->item(0)->getElementsByTagName('agent')->item(0)->nodeValue);

            return $verificationReport;
        }

        return null;

    }

    public function getValidationStatus()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext-verification" , atEppConstants::namespaceAtExtVerification );

        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext-verification:infData/at-ext-verification:status/@s');
        if (!is_null($result) && $result->length > 0) {
            return $result->item(0)->nodeValue;
        }

        return null;
    }
    
    public function getValidationActionDate()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace ( "at-ext-verification" , atEppConstants::namespaceAtExtVerification );

        $result = $xpath->query('/epp:epp/epp:response/epp:extension/at-ext-verification:infData/at-ext-verification:actionDate');
        if (!is_null($result) && $result->length > 0) {
            return $result->item(0)->nodeValue;
        }

        return null;
    }
    
    
    

}