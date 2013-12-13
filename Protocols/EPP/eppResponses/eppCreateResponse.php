<?php

class eppCreateResponse extends eppResponse
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * CONTACT CREATE RESPONSES
     */

    /**
     *
     * @return string contact_id
     */
    public function getContactId()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:creData/contact:id');
        if (is_object($result) && ($result->length > 0))
        {
            return trim($result->item(0)->nodeValue);
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
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:creData/contact:crDate');
        if (is_object($result) && ($result->length > 0))
        {
            return trim($result->item(0)->nodeValue);
        }
        else
        {
            return null;
        }
    }

    /**
     *
     * @return eppContactHandle contacthandle
     */
    public function getContactHandle()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/contact:creData/contact:id');
       $contacthandle = new eppContactHandle($result->item(0)->nodeValue);
       return $contacthandle;
    }

    /**
     * DOMAIN CREATE RESPONSES
     */

    public function getDomainCreateDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData/domain:crDate');
        if (is_object($result) && ($result->length > 0))
        {
            return trim($result->item(0)->nodeValue);
        }
        else
        {
            return null;
        }
    }
    

    public function getDomainExpirationDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData/domain:exDate');
        if (is_object($result) && ($result->length > 0))
        {
            return trim($result->item(0)->nodeValue);
        }
        else
        {
            return null;
        }
    }
    
    
    
    public function getDomainName()
    {
       $xpath = $this->xPath();
       $idna = new eppIDNA();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData/domain:name');
        if (is_object($result) && ($result->length > 0))
        {
            return trim($result->item(0)->nodeValue);
        }
        else
        {
            return null;
        }       
    }

    public function getDomain()
    {
        $return = new eppDomain($this->getDomainName());
        return $return;
    }

    /**
     * HOST CREATE RESPONSES
     */


    public function getHostName()
    {
       $xpath = $this->xPath();
       $idna = new eppIDNA();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:creData/host:name');
        if (is_object($result) && ($result->length > 0))
        {
            return trim($result->item(0)->nodeValue);
        }
        else
        {
            return null;
        }        
    }
    public function getHostCreateDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:creData/host:crDate');
        if (is_object($result) && ($result->length > 0))
        {
            return trim($result->item(0)->nodeValue);
        }
        else
        {
            return null;
        }        
    }
}