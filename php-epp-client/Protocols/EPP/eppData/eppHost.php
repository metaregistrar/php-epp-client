<?php

class eppHost
{
    const HOST_ADDR_V4 = 'v4';
    const HOST_ADDR_V6 = 'v6';

    #
    # These status values cannot be set, only viewed
    #
    const STATUS_OK = 'ok';
    const STATUS_SERVER_DELETE_PROHIBITED   = 'serverDeleteProhibited';
    const STATUS_SERVER_UPDATE_PROHIBITED   = 'serverUpdateProhibited';
    const STATUS_LINKED                     = 'linked';
    const STATUS_PENDING_CREATE             = 'pendingCreate';
    const STATUS_PENDING_DELETE             = 'pendingDelete';
    const STATUS_PENDING_TRANSFER           = 'pendingTransfer';
    const STATUS_PENDING_UPDATE             = 'pendingUpdate';

    #
    # These status values can be set
    #
    const STATUS_CLIENT_DELETE_PROHIBITED   = 'clientDeleteProhibited';
    const STATUS_CLIENT_UPDATE_PROHIBITED   = 'clientUpdateProhibited';

    /**
     * Holds the hostname of the nameserver
     * @var <string>
     */
    private $hostname;

    /**
     * Holds the IP address of the nameserver
     * @var <array> of <string>
     */
    private $ipaddresses;

    /**
     * Holds the status of the nameserver as provided by SIDN nameserver info request
     * @var <array> of <string>
     */
    private $hoststatus;

    /**
     *
     * @param <string> $hostname
     * @param <string> $ipaddress
     * @param <string> $hoststatus
     * @todo support multiple IP addresses
     */
    public function  __construct($hostname, $ipaddress = null, $hoststatus = null)
    {
        $this->setHostname($hostname);
        if (is_array($ipaddress))
        {
            foreach ($ipaddress as $ip)
            {
                if (strlen($ip))
                {
                    $this->setIpAddress($ip);
                }
            }
        }
        else
        {
            if (strlen($ipaddress))
            {
                $this->setIpAddress($ipaddress);
            }
        }
        if (is_array($hoststatus))
        {
            foreach ($hoststatus as $status)
            {
                if (strlen($status))
                {
                    $this->setHostStatus($status);
                }
            }
        }
        else
        {
            if (strlen($hoststatus))
            {
                $this->setHostStatus($hoststatus);
            }
        }
    }

    // getters
    public function getHostname()
    {
        return $this->hostname;
    }

    public function getIpAddresses()
    {
        return $this->ipaddresses;
    }

    public function getIpAddressCount()
    {
        return count($this->ipaddresses);
    }
    
    public function getHostStatusCount()
    {
        return count($this->hoststatus);
    }
    
    public function getHostStatuses()
    {
        return $this->hoststatus;
    }
    
    public function setHostStatus($hoststatus)
    {
        $this->hoststatus[] = $hoststatus;
    }

    // setters
    public function setHostname($hostname)
    {
        if (strlen($hostname)>0)
        {
            $this->hostname = $hostname;
        }
        else
        {
            throw new eppException("Hostname cannot be empty on eppHost object");
        }
    }

    public function setIpAddress($ipaddress)
    {

        if( filter_var( $ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
        {
            $this->ipaddresses[$ipaddress] = eppHost::HOST_ADDR_V6;
        }
        else if( filter_var( $ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
        {
            $this->ipaddresses[$ipaddress] = eppHost::HOST_ADDR_V4;
        }
        else
        {
            throw new eppException('IP address '.$ipaddress.' on eppHost object is not a valid IP address');
        }
    }

}
