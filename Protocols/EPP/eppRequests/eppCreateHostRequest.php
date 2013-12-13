<?php


class eppCreateHostRequest extends eppCreateRequest
{
    function __construct($createinfo)
    {
        parent::__construct($createinfo);

        if ($createinfo instanceof eppHost)
        {
            $this->setHost($createinfo);
        }
        else
        {
            throw new eppException('createinfo must be of type eppContact on eppCreateHostRequest');
        }
        $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     *
     * @param eppHost $host
     * @return domElement
     */
    public function setHost(eppHost $host)
    {
        if (!strlen($host->getHostname()))
        {
            throw new eppException('No valid hostname in create host request');
        }
        #
        # Object create structure
        #
        $create = $this->createElement('create');
        $this->hostobject = $this->createElement('host:create');
        $this->hostobject->appendChild($this->createElement('host:name',$host->getHostname()));
        $addresses = $host->getIpAddresses();
        if (is_array($addresses))
        {
            foreach ($addresses as $address=>$type)
            {
                $ipaddress = $this->createElement('host:addr',$address);
                $ipaddress->setAttribute('ip',$type);
                $this->hostobject->appendChild($ipaddress);
            }
        }
        $create->appendChild($this->hostobject);
        $this->getCommand()->appendChild($create);
        return;
    }

}

