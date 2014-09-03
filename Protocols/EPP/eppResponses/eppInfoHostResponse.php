<?php

class eppInfoHostResponse extends eppInfoResponse
{

    /**
     *
     * @return eppHost 
     */
    public function getHost()
    {
        $hostname = $this->getHostname();
        $address = $this->getHostAddresses();
        $host = new eppHost($hostname,$address);
        return $host;
    }
    /**
     *
     * @return string hostname
     */
    public function getHostName()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:name');
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
     * @return array of host addresses
     */
    public function getHostAddresses()
    {
        $ip = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:addr');
        foreach ($result as $address)
        {
           $ip[$address->nodeValue]=$address->getAttribute('ip');
        }
        return $ip;
    }

    /**
     *
     * @return string status
     */
    public function getHostStatuses()
    {
        $stat = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:status/@s');
        foreach ($result as $status)
        {
           $stat[] = $status->nodeValue;
        }
        return $stat;
    }

    /**
     *
     * @return string statuses
     */
    public function getHostStatusCSV()
    {
        return parent::arrayToCSV($this->getHostStatus());
    }

    /**
     *
     * @return string roid
     */
    public function getHostRoid()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:roid');
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
    public function getHostCreateDate()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:crDate');
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
    public function getHostUpdateDate()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:upDate');
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
    public function getHostClientId()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:clID');
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
    public function getHostCreateClientId()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:crID');
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
    public function getHostUpdateClientId()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:upID');
        if ($result->length > 0)
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }
}
