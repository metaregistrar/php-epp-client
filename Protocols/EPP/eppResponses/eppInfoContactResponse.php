<?php
namespace Metaregistrar\EPP;

class eppInfoContactResponse extends eppInfoResponse {


    /**
     *
     * @return eppContact
     */
    public function getContact() {
        $postalinfo = $this->getContactPostalInfo();
        $contact = new eppContact($postalinfo, $this->getContactEmail(), $this->getContactVoice(), $this->getContactFax());
        return $contact;
    }

    /**
     *
     * @return string contactid
     */
    public function getContactId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:id');
    }

    /**
     *
     * @return string contact_resource_id
     */
    public function getContactRoid() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:roid');
    }

    /**
     *
     * @return string client id
     */
    public function getContactClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:clID');
    }

    /**
     *
     * @return string client id
     */
    public function getContactCreateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:crID');
    }


    /**
     *
     * @return string update_date
     */
    public function getContactUpdateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:upDate');
    }

    /**
     *
     * @return string create_date
     */
    public function getContactCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:crDate');
    }


    /**
     *
     * @return string contact_status
     */
    public function getContactStatus() {
        $stat = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:status/@s');
        foreach ($result as $status) {
            $stat[] = $status->nodeValue;
        }
        return $stat;
    }

    /**
     *
     * @return array of statuses
     */
    public function getContactStatusCSV() {
        return parent::arrayToCSV($this->getContactStatus());

    }

    /**
     *
     * @return string voice_telephone_number
     */
    public function getContactVoice() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:voice');
    }

    /**
     *
     * @return string fax_telephone_number
     */
    public function getContactFax() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:fax');
    }

    /**
     *
     * @return string email_address
     */
    public function getContactEmail() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:email');
    }

    /**
     *
     * @return string contact_name
     */
    public function getContactName() {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo) {
            return $postalInfo->getName();
        } else {
            return null;
        }
    }

    public function getContactStreet() {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo) {
            return $postalInfo->getStreet(0);
        } else {
            return null;
        }
    }

    public function getContactCity() {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo) {
            return $postalInfo->getCity();
        } else {
            return null;
        }
    }

    public function getContactZipcode() {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo) {
            return $postalInfo->getZipcode();
        } else {
            return null;
        }
    }

    public function getContactProvince() {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo) {
            return $postalInfo->getProvince();
        } else {
            return null;
        }
    }

    public function getContactCountrycode() {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo) {
            return $postalInfo->getCountrycode();
        } else {
            return null;
        }

    }


    /**
     *
     * @return string company_name
     */
    public function getContactCompanyname() {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo) {
            return $postalInfo->getOrganisationName();
        } else {
            return null;
        }
    }

    /**
     *
     * @return string postal type
     */
    public function getContactPostalType() {
        $returntype = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:postalInfo/@type');
        foreach ($result as $type) {
            $returntype = $type->nodeValue;
        }
        return $returntype;
    }

    public function getContactDisclose() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:disclose/@flag');
    }

    /**
     *
     * @return string client id
     */
    public function getContactUpdateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:upID');
    }

    public function getContactAuthInfo() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/contact:infData/contact:authInfo/contact:pw');
    }

    /**
     *
     * @return array
     */
    public function getContactPostalInfo() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:postalInfo');
        $postalinfo = [];
        foreach ($result as $postalresult) {
            /* @var $postalresult \DOMElement */
            $testtype = $postalresult->getAttributeNode('type');
            $type = eppContact::TYPE_LOC;
            if ($testtype) {
                $type = $testtype->value;
            }
            $testname = $postalresult->getElementsByTagName('name');
            $name = null;
            if ($testname->length > 0) {
                $name = $testname->item(0)->nodeValue;
            }
            $testorg = $postalresult->getElementsByTagName('org');
            $org = null;
            if ($testorg->length > 0) {
                $org = $testorg->item(0)->nodeValue;
            }
            $city = null;
            $country = null;
            $zipcode = null;
            $province = null;
            $streets = null;
            $testaddr = $postalresult->getElementsByTagName('addr');
            if ($testaddr->length > 0) {
                $addr = $testaddr->item(0);
                /* @var $addr \DOMElement */
                $testcity = $addr->getElementsByTagName('city');
                /* @var $postalresult \DOMElement */
                
                if ($testcity->length > 0) {
                    $city = $testcity->item(0)->nodeValue;
                }
                $testcc = $addr->getElementsByTagName('cc');
                
                if ($testcc->length > 0) {
                    $country = $testcc->item(0)->nodeValue;
                }
                $testpc = $addr->getElementsByTagName('pc');
                
                if ($testpc->length > 0) {
                    $zipcode = $testpc->item(0)->nodeValue;
                }
                $testsp = $addr->getElementsByTagName('sp');
                
                if ($testsp->length > 0) {
                    $province = $testsp->item(0)->nodeValue;
                }
                $teststreet = $addr->getElementsByTagName('street');
                if ($teststreet->length > 0) {
                    foreach ($teststreet as $street) {
                        $streets[] = $street->nodeValue;
                    }
                }
            }
            $postalinfo[] = new eppContactPostalInfo($name, $city, $country, $org, $streets, $province, $zipcode, $type);
        }
        return $postalinfo;
    }


}
