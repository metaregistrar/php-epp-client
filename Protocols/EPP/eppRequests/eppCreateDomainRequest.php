<?php


class eppCreateDomainRequest extends eppCreateRequest
{

    private $forcehostattr = false;

    function __construct($createinfo)
    {
        parent::__construct($createinfo);

        if ($createinfo instanceof eppDomain)
        {
            $this->setDomain($createinfo);
        }
        else
        {
            throw new eppException('createinfo must be of type eppDomain on eppCreateDomainRequest');
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

    /*
     * @param eppSecdns $secdns
     */
    public function addSecdns($secdns)
    {
        /* @var eppSecDNS $secdns */
        if (!$this->extension)
        {
            $this->extension = $this->createElement('extension');
            $this->getCommand()->appendChild($this->extension);
        }
        $seccreate = $this->createElement('secDNS:create');
        $seccreate->setAttribute('xmlns:secDNS','urn:ietf:params:xml:ns:secDNS-1.1');
        if ($secdns->getKeytag())
        {
            /*
             * Keytag found, assuming client wants to add dnssec data via dsData interface
             * http://tools.ietf.org/search/rfc5910#section-4.1
             */
            $secdsdata = $this->createElement('secDNS:dsData');
            $secdsdata->appendChild($this->createElement('secDNS:keyTag',$secdns->getKeytag()));
            $secdsdata->appendChild($this->createElement('secDNS:alg',$secdns->getAlgorithm()));
            $secdsdata->appendChild($this->createElement('secDNS:digestType', $secdns->getDigestType()));
            $secdsdata->appendChild($this->createElement('secDNS:digest',$secdns->getDigest()));
            if ($secdns->getPubkey())
            {
                /*
                 * Pubkey found, adding option key data to the request
                 */
                $seckeydata = $this->createElement('secDNS:keyData');
                $seckeydata->appendChild($this->createElement('secDNS:flags',$secdns->getFlags()));
                $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
                $seckeydata->appendChild($this->createElement('secDNS:alg',$secdns->getAlgorithm()));
                $seckeydata->appendChild($this->createElement('secDNS:pubKey',$secdns->getPubkey()));
                $secdsdata->appendChild($seckeydata);
            }
            $seccreate->appendChild($secdsdata);
        }
        else
        {
            /*
             * Keytag not found, assuming client wants to add dnssec data via keyData interface
             * http://tools.ietf.org/search/rfc5910#section-4.2
             */
            $seckeydata = $this->createElement('secDNS:keyData');
            $seckeydata->appendChild($this->createElement('secDNS:flags',$secdns->getFlags()));
            $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
            $seckeydata->appendChild($this->createElement('secDNS:alg',$secdns->getAlgorithm()));
            $seckeydata->appendChild($this->createElement('secDNS:pubKey',$secdns->getPubkey()));
            $seccreate->appendChild($seckeydata);
        }
        $this->extension->appendChild($seccreate);

        // Put session id at the end of the EPP command chain
        $this->addSessionId();
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
        $this->getCommand()->appendChild($create);
        // Check for DNSSEC keys and add them
        if ($domain->getSecdnsLength()>0)
        {
            for ($i = 0; $i < $domain->getSecdnsLength(); $i++)
            {
                $sd = $domain->getSecdns($i);
                /* @var $sd riSecdns */
                if ($sd)
                {
                    $ext = new eppSecdns();
                    $ext->copy($sd);
                    $this->addSecdns($ext);
                }
            }
        }
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

