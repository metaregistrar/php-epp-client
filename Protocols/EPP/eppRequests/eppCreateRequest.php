<?php
include_once(dirname(__FILE__).'/../eppRequest.php');
/*
 * This object contains all the logic to create an EPP create command
 */


class eppCreateRequest extends eppRequest
{

    private $forcehostattr = false;

    function __construct($createinfo)
    {
        parent::__construct();

        if ($createinfo instanceof eppDomain)
        {
            $this->setDomain($createinfo);
        }
        else
        {
            if ($createinfo instanceof eppContact)
            {
                $this->setContact($createinfo);
            }
            else
            {
                if ($createinfo instanceof eppHost)
                {
                    $this->setHost($createinfo);  
                }
                else
                {
                    throw new eppException('createinfo must be of type eppDomain, eppContact or eppHost');
                }
            }
        }
        $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function getForcehostattr()
    {
        return $this->forcehostattr;
    }

    public function setForcehostattr($forcehostattr)
    {
        $this->forcehostattr = $forcehostattr;
    }

        /**
     *
     * @param eppDomain $domain
     * @return domElement
     */
    public function setDomain(eppDomain $domain)
    {
        if (!strlen($domain->getDomainname()))
        {
            throw new eppException('No valid domain name in create domain request');
        }
        if (!strlen($domain->getRegistrant()))
        {
            throw new eppException('No valid registrant in create domain request');
        }
        #
        # Create command structure
        #
        $this->command = $this->createElement('command');
        #
        # Object create structure
        #
        $create = $this->createElement('create');
        $this->domainobject = $this->createElement('domain:create');
        $this->domainobject->appendChild($this->createElement('domain:name',$domain->getDomainname()));
        if ($domain->getPeriod()>0)
        {
            $domainperiod = $this->createElement('domain:period',$domain->getPeriod());
            $domainperiod->setAttribute('unit',$domain->getPeriodUnit());
            $this->domainobject->appendChild($domainperiod);
        }
        $nsobjects = $domain->getHosts();
        if ($domain->getHostLength()>0)
        {
            $nameservers = $this->createElement('domain:ns');
            foreach ($nsobjects as $nsobject)
            {
                 if (($this->forcehostattr) || ($nsobject->getIpAddressCount()>0))
                 {
                    $nameservers->appendChild($this->addDomainHostAttr($nsobject));
                 }
                 else
                 {
                     $nameservers->appendChild($this->addDomainHostObj($nsobject));
                 }
            }
            $this->domainobject->appendChild($nameservers);
        }
        $this->domainobject->appendChild($this->createElement('domain:registrant',$domain->getRegistrant()));
        $contacts = $domain->getContacts();
        if ($domain->getContactLength()>0)
        {
            foreach ($contacts as $contact)
            {
                $this->addDomainContact($this->domainobject,$contact->getContactHandle(),$contact->getContactType());
            }
        }
        if (strlen($domain->getAuthorisationCode()))
        {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw',$domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $create->appendChild($this->domainobject);
        $this->command->appendChild($create);
        $this->epp->appendChild($this->command);
        return;
    }

    /**
     *
     * @param eppContact $contact
     * @return domElement
     */
    public function setContact(eppContact $contact)
    {
        #
        # Create command structure
        #
        $this->command = $this->createElement('command');
        #
        # Object create structure
        #
        $create = $this->createElement('create');

        $this->contactobject = $this->createElement('contact:create');
        $this->contactobject->appendChild($this->createElement('contact:id',$contact->generateContactId()));
        $postalinfo = $this->createElement('contact:postalInfo');
        $postal = $contact->getPostalInfo(0);
        $postalinfo->setAttribute('type',$postal->getType());
        $postalinfo->appendChild($this->createElement('contact:name',$postal->getName()));
        if ($postal->getOrganisationName())
        {
            $postalinfo->appendChild($this->createElement('contact:org',$postal->getOrganisationName()));
        }
        $postaladdr = $this->createElement('contact:addr');
        $count = $postal->getStreetCount();
        for ($i = 0; $i < $count; $i++)
        {
            $postaladdr->appendChild($this->createElement('contact:street',$postal->getStreet($i)));
        }
        $postaladdr->appendChild($this->createElement('contact:city',$postal->getCity()));
        if ($postal->getProvince())
        {
            $postaladdr->appendChild($this->createElement('contact:sp',$postal->getProvince()));
        }
        $postaladdr->appendChild($this->createElement('contact:pc',$postal->getZipcode()));
        $postaladdr->appendChild($this->createElement('contact:cc',$postal->getCountrycode()));
        $postalinfo->appendChild($postaladdr);
        $this->contactobject->appendChild($postalinfo);
        $this->contactobject->appendChild($this->createElement('contact:voice',$contact->getVoice()));
        if ($contact->getFax())
        {
            $this->contactobject->appendChild($this->createElement('contact:fax',$contact->getFax()));
        }
        $this->contactobject->appendChild($this->createElement('contact:email',$contact->getEmail()));
        $authinfo = $this->createElement('contact:authInfo');
        $authinfo->appendChild($this->createElement('contact:pw','foo2bar'));
        $this->contactobject->appendChild($authinfo);
        $create->appendChild($this->contactobject);
        $this->command->appendChild($create);
        
        $this->epp->appendChild($this->command);
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
        # Create command structure
        #
        $this->command = $this->createElement('command');
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
        $this->command->appendChild($create);
        $this->epp->appendChild($this->command);
        return;
    }

    /**
     *
     * @param string $domain
     * @param string $contactid
     * @param string $contacttype
     */
    private function addDomainContact($domain,$contactid,$contacttype)
    {
        $domaincontact = $this->createElement('domain:contact',$contactid);
        $domaincontact->setAttribute('type',$contacttype);
        $domain->appendChild($domaincontact);
    }

    /**
     *
     * @param eppHost $host
     * @return domElement
     */
    private function addDomainHostAttr(eppHost $host)
    {

        $ns = $this->createElement('domain:hostAttr');
        $ns->appendChild($this->createElement('domain:hostName',$host->getHostname()));
        if ($host->getIpAddressCount()>0)
        {
            $addresses = $host->getIpAddresses();
            foreach ($addresses as $address=>$type)
            {
                $ip = $this->createElement('domain:hostAddr',$address);
                $ip->setAttribute('ip',$type);
                $ns->appendChild($ip);
            }
        }
        return $ns;
    }
    /**
     *
     * @param eppHost $host
     * @return domElement
     */
    private function addDomainHostObj(eppHost $host)
    {
        $ns = $this->createElement('domain:hostObj',$host->getHostname());
        return $ns;
    }
}

