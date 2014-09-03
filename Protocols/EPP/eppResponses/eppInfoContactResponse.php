<?php

class eppInfoContactResponse extends eppInfoResponse
{


    /**
     *
     * @return eppContact 
     */
    public function getContact()
    {
        $postalinfo = $this->getContactPostalInfo();
        $contact = new eppContact($postalinfo,$this->getContactEmail(),$this->getContactVoice(),$this->getContactFax());
        return $contact;
    }
    /**
     *
     * @return string contactid
     */
    public function getContactId()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:id');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }
    
    /**
     *
     * @return string contact_resource_id
     */
    public function getContactRoid()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:roid');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }
    /**
     *
     * @return string client id
     */
    public function getContactClientId()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:clID');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }

    /**
     *
     * @return string client id
     */
    public function getContactCreateClientId()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:crID');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }
    
    
    /**
     *
     * @return string update_date
     */
    public function getContactUpdateDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:upDate');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }    
    /**
     *
     * @return string create_date
     */
    public function getContactCreateDate()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:crDate');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }


    /**
     *
     * @return string contact_status
     */
    public function getContactStatus()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:status/@s');
       foreach ($result as $status)
       {
           $stat[] = $status->nodeValue;
       }
       return $stat;
    }

    /**
     *
     * @return array of statuses
     */
    public function getContactStatusCSV()
    {
        return parent::arrayToCSV($this->getContactStatus());

    }
    /**
     *
     * @return string voice_telephone_number
     */
    public function getContactVoice()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:voice');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
        
    }
    /**
     *
     * @return string fax_telephone_number
     */
    public function getContactFax()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:fax');
       if ($result->length > 0)
       {
            return $result->item(0)->nodeValue;
       }
       else
       {
           return null;
       }
    }
    /**
     *
     * @return string email_address
     */
    public function getContactEmail()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:email');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }

    /**
     *
     * @return string contact_name
     */
    public function getContactName()
    {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo)
        {
            return $postalInfo->getName();
        }
        else
        {
            return null;
        }
    }
    
    public function getContactStreet()
    {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo)
        {
            return $postalInfo->getStreet(0);
        }
        else
        {
            return null;
        }
    }
    
    public function getContactCity()
    {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo)
        {
            return $postalInfo->getCity();
        }
        else
        {
            return null;
        }
    }
    
    public function getContactZipcode()
    {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo)
        {
            return $postalInfo->getZipcode();
        }
        else
        {
            return null;
        }
    }
    
    public function getContactProvince()
    {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo)
        {
            return $postalInfo->getProvince();
        }
        else
        {
            return null;
        }
    }
    
    public function getContactCountrycode()
    {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo)
        {
            return $postalInfo->getCountrycode(); 
        }
        else
        {
            return null;
        }
               
    }
    
    
    /**
     *
     * @return string company_name
     */
    public function getContactCompanyname()
    {
        $pi = $this->getContactPostalInfo();
        $postalInfo = $pi[0];
        if ($postalInfo instanceof eppContactPostalInfo)
        {
            return $postalInfo->getOrganisationName();
        }
        else
        {
            return null;
        }
    }

    /**
     *
     * @return string postal type
     */
    public function getContactPostalType()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:postalInfo/@type');
        foreach ($result as $type)
        {
           $returntype = $type->nodeValue;
        }
        return $returntype;
    }

    /**
     *
     * @return string client id
     */
    public function getContactUpdateClientId()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:upID');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }    
    
   
    /**
     *
     * @return array
     */
    public function getContactPostalInfo()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:infData/contact:postalInfo');
        foreach ($result as $postalresult)
        {            
            $testtype = $postalresult->getAttributeNode('type');
            $type = eppContactPostalInfo::POSTAL_TYPE_LOCAL;
            if ($testtype)
            {
                $type = $testtype->value;
            }
            $testname = $postalresult->getElementsByTagName('name');
            $name = null;
            if ($testname->length>0)
            {
                $name = $testname->item(0)->nodeValue;
            }
            $testorg = $postalresult->getElementsByTagName('org');
            $org = null;
            if ($testorg->length>0)
            {
                $org = $testorg->item(0)->nodeValue;
            }
            $testaddr = $postalresult->getElementsByTagName('addr');
            if ($testaddr->length>0)
            {
                $addr = $testaddr->item(0);
                $testcity = $addr->getElementsByTagName('city');
                $city = null;
                if ($testcity->length>0)
                {
                    $city = $testcity->item(0)->nodeValue;
                }
                $testcc = $addr->getElementsByTagName('cc');
                $country = null;
                if ($testcc->length>0)
                {
                    $country = $testcc->item(0)->nodeValue;
                }
                $testpc = $addr->getElementsByTagName('pc');
                $zipcode = null;
                if ($testpc->length>0)
                {
                    $zipcode = $testpc->item(0)->nodeValue;
                }
                $testsp = $addr->getElementsByTagName('sp');
                $province = null;
                if ($testsp->length>0)
                {
                    $province = $testsp->item(0)->nodeValue;
                }
                $teststreet = $addr->getElementsByTagName('street');
                $streets = null;
                if ($teststreet->length>0)
                {
                    foreach ($teststreet as $street)
                    {
                        $streets[] = $street->nodeValue;
                    }
                }
            }
            $postalinfo[] = new eppContactPostalInfo($name,$city,$country,$org,$streets,$province,$zipcode, $type);
        }
        return $postalinfo;
    }

   
    
}
