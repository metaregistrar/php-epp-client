<?php
namespace Metaregistrar\EPP;

class metaregSslValidation {
    /**
     *
     * @var string
     */
    private $type = null;
    /**
     *
     * @var string
     */
    private $status = null;

    /**
     * @var string
     */
    private $statusmessage = null;

    private $hostname = null;
    private $validationtype = null;
    private $hoststatus = null;
    private $hoststatusmessage = null;
    private $filelocation = null;
    private $filecontents = null;
    private $dnsrecord = null;
    private $cnamevalue = null;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusmessage;
    }

    /**
     * @param string $statusmessage
     */
    public function setStatusMessage($statusmessage)
    {
        $this->statusmessage = $statusmessage;
    }

    /**
     * @return null
     */
    public function getHostName()
    {
        return $this->hostname;
    }

    /**
     * @param null $hostname
     */
    public function setHostName($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return null
     */
    public function getValidationType()
    {
        return $this->validationtype;
    }

    /**
     * @param null $validationtype
     */
    public function setValidationType($validationtype)
    {
        $this->validationtype = $validationtype;
    }

    /**
     * @return null
     */
    public function getHostStatus()
    {
        return $this->hoststatus;
    }

    /**
     * @param null $hoststatus
     */
    public function setHostStatus($hoststatus)
    {
        $this->hoststatus = $hoststatus;
    }

    /**
     * @return null
     */
    public function getHostStatusMessage()
    {
        return $this->hoststatusmessage;
    }

    /**
     * @param null $hoststatusmessage
     */
    public function setHostStatusMessage($hoststatusmessage)
    {
        $this->hoststatusmessage = $hoststatusmessage;
    }

    /**
     * @return null
     */
    public function getFileLocation()
    {
        return $this->filelocation;
    }

    /**
     * @param null $filelocation
     */
    public function setFileLocation($filelocation)
    {
        $this->filelocation = $filelocation;
    }

    /**
     * @return null
     */
    public function getFileContents()
    {
        return $this->filecontents;
    }

    /**
     * @param null $filecontents
     */
    public function setFileContents($filecontents)
    {
        $this->filecontents = $filecontents;
    }

    /**
     * @return null
     */
    public function getDnsRecord()
    {
        return $this->dnsrecord;
    }

    /**
     * @param null $dnsrecord
     */
    public function setDnsRecord($dnsrecord)
    {
        $this->dnsrecord = $dnsrecord;
    }

    /**
     * @return null
     */
    public function getCnameValue()
    {
        return $this->cnamevalue;
    }

    /**
     * @param null $cnamevalue
     */
    public function setCnameValue($cnamevalue)
    {
        $this->cnamevalue = $cnamevalue;
    }



}