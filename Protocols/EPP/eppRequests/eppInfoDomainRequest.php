<?php
include_once(dirname(__FILE__).'/eppRequest.php');
/*
 * This object contains all the logic to create an EPP domain:info command
 */

class eppInfoDomainRequest extends eppRequest
{
    const HOSTS_ALL = 'all';
    const HOSTS_DELEGATED = 'del';
    const HOSTS_SUBORDINATE = 'sub';
    const HOSTS_NONE = 'none';
    
    
    public function __construct($infodomain, $hosts = null)
    {
        parent::__construct();

        if ($infodomain instanceof eppDomain)
        {
            $this->setDomain($infodomain, $hosts);
        }
        else
        {
            throw new eppException('parameter of infodomainrequest needs to be eppDomain object');
        }
            $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
    public function setDomain(eppDomain $domain, $hosts = null)
    {
        if (!strlen($domain->getDomainName()))
        {
            throw new eppException('Domain object does not contain a valid domain name');
        }
        #
        # Create command structure
        #
        $this->command = $this->createElement('command');
        #
        # Domain check structure
        #
        $info = $this->createElement('info');
        $this->domainobject = $this->createElement('domain:info');
        $info->appendChild($this->domainobject);

        $dname = $this->createElement('domain:name',$domain->getDomainName());
        if ($hosts)
        {
            if (($hosts == self::HOSTS_ALL) || ($hosts == self::HOSTS_DELEGATED)|| ($hosts == self::HOSTS_NONE) || ($hosts == self::HOSTS_SUBORDINATE))
            {
                $dname->setAttribute('hosts',$hosts);
            }
            else
            {
                throw new eppException('Hosts parameter of inforequest can only be to be all, none, del or sub');
            }
        }
        else
        {
            $dname->setAttribute('hosts',self::HOSTS_ALL);
        }
        $this->domainobject->appendChild($dname);
        if (!$this->command)
        {
            $this->command = $this->getCommand();
        }
        $this->command->appendChild($info);
        if (!$this->epp)
        {
            $this->epp = $this->getEpp();
        }
        $this->epp->appendChild($this->command);
    }    
}
