<?php
namespace Metaregistrar\EPP;

class ficoraEppInfoContactResponse extends eppInfoContactResponse {

    /**
     * A helper function for retriving xpath query results.
     * @param $query
     * @param null $cast
     * @return mixed query result as string or casted type or null if missing
     */
    protected function getXpathQueryResult($query, $cast = null)
    {
        $xpath = $this->xPath();
        $result = $xpath->query($query);
        if ($result->length > 0) {
            $value = $result->item(0)->nodeValue;
            if ($cast) {
                settype($value, $cast);
            }
            return $value;
        } else {
            return null;
        }
    }

    /**
     *
     * @return int|null role
     */
    public function getContactRole()
    {
        return $this->getXpathQueryResult('/epp:epp/epp:response/epp:resData/contact:infData/contact:role', 'integer');
    }

    /**
     *
     * @return int|null type
     */
    public function getContactType()
    {
        return $this->getXpathQueryResult('/epp:epp/epp:response/epp:resData/contact:infData/contact:type', 'integer');
    }

    /**
     *
     * @return string|null legalemail
     */
    public function getContactLegalEmail()
    {
        return $this->getXpathQueryResult('/epp:epp/epp:response/epp:resData/contact:infData/contact:legalemail');
    }

    /**
     *
     * @return ficoraEppContactPostalInfo[] postal info elements
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

            // special handling for ficora specific elements
            $firstName = $this->getElementValueByTagNameOrDefault($postalresult, 'firstname');
            $lastName = $this->getElementValueByTagNameOrDefault($postalresult, 'lastname');
            $isFinnish = $this->getElementValueByTagNameOrDefault($postalresult, 'isFinnish');
            $birthDate = $this->getElementValueByTagNameOrDefault($postalresult, 'birthDate');
            $identity = $this->getElementValueByTagNameOrDefault($postalresult, 'identity');
            $registerNumber = $this->getElementValueByTagNameOrDefault($postalresult, 'registernumber');

            $postalinfo[] = new ficoraEppContactPostalInfo(
                $name,
                $city,
                $country,
                $org,
                $streets,
                $province,
                $zipcode,
                $type,
                $firstName,
                $lastName,
                $isFinnish,
                $identity,
                $birthDate,
                $registerNumber
            );
        }
        return $postalinfo;
    }

    /**
     * Returns value of first matching element or default value if no matches
     * @param  \DOMElement $element      Element to query
     * @param  string      $tagName      Tag name
     * @param  mixed      $defaultValue Default value 
     * @return mixed                    Element value or default value
     */
    private function getElementValueByTagNameOrDefault(\DOMElement $element, $tagName, $defaultValue = null)
    {
        $result = $element->getElementsByTagName($tagName);
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        }

        return $defaultValue;
    }
}