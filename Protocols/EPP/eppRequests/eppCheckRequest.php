<?php

class eppCheckRequest extends eppRequest
{
    function __construct($checkrequest)
    {
        parent::__construct();
        
        if ($checkrequest instanceof eppDomain)
        {
            $this->setDomainNames(array($checkrequest));
        }
        else
        {
            if ($checkrequest instanceof eppContactHandle)
            {
                $this->setContactHandles(array($checkrequest));
            }
            else
            {
                if ($checkrequest instanceof eppHost)
                {
                    $this->setHosts(array($checkrequest));
                }
                else
                {
                    if (is_array($checkrequest))
                    {
                        if ($checkrequest[0] instanceof eppDomain)
                        {
                            $this->setDomainNames($checkrequest);
                        }
                        else
                        {
                            if ($checkrequest[0] instanceof eppContactHandle)
                            {
                                $this->setContactHandles($checkrequest);
                            }
                            else
                            {
                                if ($checkrequest[0] instanceof eppHost)
                                {
                                    $this->setHosts($checkrequest);
                                }
                                else
                                {
                                    $this->setDomainNames($checkrequest);
                                }
                            }
                        }

                    }
                }
            }
        }      
    }

    function __destruct()
    {
        parent::__destruct();
    }


    /**
     *
     * @param array $domainnames
     */
    public function setDomainNames($domains)
    {
        #
        # Domain check structure
        #
        $check = $this->createElement('check');
        $this->domainobject = $this->createElement('domain:check');
        foreach ($domains as $domain)
        {
            if ($domain instanceof eppDomain)
            {
                $this->domainobject->appendChild($this->createElement('domain:name',$domain->getDomainName()));
            }
            else
            {
                $this->domainobject->appendChild($this->createElement('domain:name',$domain));
            }
        }
        $check->appendChild($this->domainobject);
        $this->getCommand()->appendChild($check);
    }

    public function setContactHandles($contacthandles)
    {
        #
        # Domain check structure
        #
        $check = $this->createElement('check');
        $this->contactobject = $this->createElement('contact:check');
        foreach ($contacthandles as $contacthandle)
        {
            if ($contacthandle instanceof eppContactHandle)
            {
                $this->contactobject->appendChild($this->createElement('contact:id',$contacthandle->getContactHandle()));
            }
            else
            {
                $this->contactobject->appendChild($this->createElement('contact:id',$contacthandle));
            }
        }
        $check->appendChild($this->contactobject);
        $this->getCommand()->appendChild($check);
    }

    public function setHosts($hosts)
    {
        #
        # Domain check structure
        #
        $check = $this->createElement('check');
        $this->hostobject = $this->createElement('host:check');
        foreach ($hosts as $host)
        {
            if ($host instanceof eppHost)
            {
                if (strlen($host->getHostname())>0)
                {
                    $this->hostobject->appendChild($this->createElement('host:name',$host->getHostname()));
                }
                else
                {
                    throw new eppException("Empty hostobject on checkRequest creation");
                }
            }
        else
            {
                if (strlen($host)>0)
                {
                    $this->hostobject->appendChild($this->createElement('host:name',$host));
                }
                else
                {
                    throw new eppException("Empty hostname on checkRequest creation");
                }
            }
        }
        $check->appendChild($this->hostobject);
        $this->getCommand()->appendChild($check);
    }
    
 
}