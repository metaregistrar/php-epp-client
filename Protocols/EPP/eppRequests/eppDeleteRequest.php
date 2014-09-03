<?php

class eppDeleteRequest extends eppRequest
{

    function __construct($deleteinfo)
    {
        parent::__construct();


        if ($deleteinfo instanceof eppHost)
        {
            $this->setHost($deleteinfo);
        }
        else
        {
            if ($deleteinfo instanceof eppDomain)
            {
                $this->setDomain($deleteinfo);
            }
            else
            {
                if ($deleteinfo instanceof eppContactHandle)
                {
                    $this->setContactHandle($deleteinfo);
                }
                else
                {
                    throw new eppException('parameter of eppDeleteRequest must be valid eppDomain, eppContactHandle or eppHost object');
                }
            }
        }
    $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }


    public function setDomain(eppDomain $domain)
    {
        if (!strlen($domain->getDomainname()))
        {
            throw new eppException('eppDeleteRequest domain object does not contain a valid domain name');
        }
        #
        # Object delete structure
        #
        $this->domainobject = $this->createElement('delete');
        $domaindelete = $this->createElement('domain:delete');
        $domaindelete->appendChild($this->createElement('domain:name',$domain->getDomainname()));
        $this->domainobject->appendChild($domaindelete);
        $this->getCommand()->appendChild($this->domainobject);
    }


    public function setContactHandle(eppContactHandle $contacthandle)
    {
        if (!strlen($contacthandle->getContactHandle()))
        {
            throw new eppException('eppDeleteRequest contacthandle object does not contain a valid contacthandle');
        }
        #
        # Object delete structure
        #
        $this->contactobject = $this->createElement('delete');
        $contactdelete = $this->createElement('contact:delete');
        $contactdelete->appendChild($this->createElement('contact:id',$contacthandle->getContactHandle()));
        $this->contactobject->appendChild($contactdelete);
        $this->getCommand()->appendChild($this->contactobject);
    }


    public function setHost(eppHost $host)
    {
        if (!strlen($host->getHostname()))
        {
            throw new eppException('eppDeleteRequest host object does not contain a valid hostname');
        }
        #
        # Object delete structure
        #
        $this->hostobject = $this->createElement('delete');

        $hostdelete = $this->createElement('host:delete');
        $hostdelete->appendChild($this->createElement('host:name',$host->getHostname()));
        $this->hostobject->appendChild($hostdelete);
        $this->getCommand()->appendChild($this->hostobject);
    }

}