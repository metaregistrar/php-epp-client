<?php
include_once(dirname(__FILE__).'/../eppRequest.php');
/*
 * This object contains all the logic to create an EPP hello command
 */

class eppUpdateRequest extends eppRequest
{
    function __construct($objectname,$addinfo=null,$removeinfo=null,$updateinfo=null)
    {
        parent::__construct();

        if ($objectname instanceof eppDomain)
        {
            $domainname = $objectname->getDomainName();
        }
        else
        {
            if ($objectname instanceof eppContactHandle)
            {
                $contacthandle = $objectname->getContactHandle();
            }
            else
            {
                if ($objectname instanceof eppHost)
                {
                    $hostname = $objectname->getHostname();
                }
                else
                {
                    if (strlen($objectname))
                    {
                        $domainname = $objectname;
                        $contacthandle = $objectname;
                        $hostname = $objectname;
                    }
                    else
                    {
                        throw new eppException("Object name must be valid string on eppUpdateRequest");
                    }
                }
            }
        }

        if (($addinfo instanceof eppDomain) || ($removeinfo instanceof eppDomain) || ($updateinfo instanceof eppDomain) || ($this instanceof eppDnssecUpdateRequest))
        {
            $this->updateDomain($domainname,$addinfo,$removeinfo,$updateinfo);
        }
        else
        {
            if (($addinfo instanceof eppContact) || ($removeinfo instanceof eppContact) || ($updateinfo instanceof eppContact))
            {
                $this->updateContact($contacthandle,$addinfo,$removeinfo,$updateinfo);
            }
            else
            {
                if (($addinfo instanceof eppHost) || ($removeinfo instanceof eppHost) || ($updateinfo instanceof eppHost))
                {
                    $this->updateHost($hostname,$addinfo,$removeinfo,$updateinfo);
                }
                else
                {
                    throw new eppException('addinfo, removeinfo and updateinfo need to be eppDomain, eppContact or eppHost objects');
                }
            }
        }
        $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }


    /**
     *
     * @param string $domainname
     * @param eppDomain $addInfo
     * @param eppDomain $removeInfo
     * @param eppDomain $updateInfo
     * @return domElement
     */
    public function updateDomain($domainname, $addInfo, $removeInfo, $updateInfo)
    {
        #
        # Create command structure
        #
        $this->command = $this->createElement('command');
        #
        # Object create structure
        #
        $update = $this->createElement('update');
        $this->domainobject = $this->createElement('domain:update');
        $this->domainobject->appendChild($this->createElement('domain:name',$domainname));
        if ($addInfo instanceof eppDomain)
        {
            $addcmd = $this->createElement('domain:add');
            $this->addDomainChanges($addcmd,$addInfo);
            $this->domainobject->appendChild($addcmd);
        }
        if ($removeInfo instanceof eppDomain)
        {
            $remcmd = $this->createElement('domain:rem');
            $this->addDomainChanges($remcmd,$removeInfo);
            $this->domainobject->appendChild($remcmd);
        }
        if ($updateInfo instanceof eppDomain)
        {
            $chgcmd = $this->createElement('domain:chg');
            $this->addDomainChanges($chgcmd,$updateInfo);
            $this->domainobject->appendChild($chgcmd);
        }
        $update->appendChild($this->domainobject);
        $this->command->appendChild($update);
        $this->epp->appendChild($this->command);
    }

    /**
     *
     * @param domElement $element
     * @param eppDomain $domain
     */
    private function addDomainChanges($element, eppDomain $domain)
    {
        if ($domain->getRegistrant())
        {
            $element->appendChild($this->createElement('domain:registrant',$domain->getRegistrant()));
        }
        $hosts = $domain->getHosts();
        if (is_array($hosts) && (count($hosts)))
        {
            $nameservers = $this->createElement('domain:ns');
            foreach ($hosts as $host)
            {
                if (is_array($host->getIpAddresses()))
                {
                    $nameservers->appendChild($this->addDomainHostAttr($host));
                }
                else
                {
                    $nameservers->appendChild($this->addDomainHostObj($host));
                }
            }
            $element->appendChild($nameservers);
        }
        $contacts = $domain->getContacts();
        if (is_array($contacts))
        {
            foreach ($contacts as $contact)
            {
                $this->addDomainContact($element,$contact->getContactHandle(),$contact->getContactType());
            }
        }
        $statuses = $domain->getStatuses();
        if (is_array($statuses))
        {
            foreach ($statuses as $status)
            {
                $this->addDomainStatus($element, $status);
            }
        }
        if (strlen($domain->getAuthorisationCode()))
        {
            $authinfo = $this->createElement('domain:authInfo');
            $pw = $this->createElement('domain:pw');
            $pw->appendChild($this->createCDATASection($domain->getAuthorisationCode()));
            $authinfo->appendChild($pw);
            $element->appendChild($authinfo);
        }
    }


     /**
     *
     * @param domElement $element
     * @param string $status
     */
    private function addDomainStatus($element, $status)
    {
        $stat = $this->createElement('domain:status');
        $stat->setAttribute('s',$status);
        $element->appendChild($stat);
    }

    /**
     *
     * @param string $contactid
     * @param eppContact $addinfo
     * @param eppContact $removeinfo
     * @param eppContact $updateinfo
     * @return domElement
     */
    public function updateContact($contactid, $addInfo, $removeInfo, $updateInfo)
    {
        #
        # Create command structure
        #
        $this->command = $this->createElement('command');
        #
        # Object create structure
        #
        $update = $this->createElement('update');
        $this->contactobject = $this->createElement('contact:update');
        $this->contactobject->appendChild($this->createElement('contact:id',$contactid));
        if ($updateInfo instanceof eppContact)
        {
            $chgcmd = $this->createElement('contact:chg');
            $this->addContactChanges($chgcmd,$updateInfo);
            $this->contactobject->appendChild($chgcmd);
        }
        if ($removeInfo instanceof eppContact)
        {
            $remcmd = $this->createElement('contact:rem');
            $this->addContactStatus($remcmd,$removeInfo);
            $this->contactobject->appendChild($remcmd);
        }
        if ($addInfo instanceof eppContact)
        {
            $addcmd = $this->createElement('contact:add');
            $this->addContactStatus($addcmd,$addInfo);
            $this->contactobject->appendChild($addcmd);
        }
        $update->appendChild($this->contactobject);
        $this->command->appendChild($update);
        $this->epp->appendChild($this->command);
    }

    /**
     *
     * @param type $element
     * @param eppContact $contact
     */
    private function addContactStatus($element, eppContact $contact)
    {
        if ((is_array($contact->getStatus())) && (count($contact->getStatus())>0))
        {
            $statuses = $contact->getStatus();
            foreach ($statuses as $status)
            {
                $stat = $this->createElement('contact:status');
                $stat->setAttribute('s',$status);
                $element->appendChild($stat);
            }
        }
    }


    /**
     *
     * @param domElement $element
     * @param eppContact $contact
     */
    private function addContactChanges($element, eppContact $contact)
    {

        if ($contact->getPostalInfoLength()>0)
        {
            $postal = $contact->getPostalInfo(0);
            $postalinfo = $this->createElement('contact:postalInfo');
            $postalinfo->setAttribute('type',$postal->getType());
            if (strlen($postal->getName()))
            {
                $postalinfo->appendChild($this->createElement('contact:name',$postal->getName()));
            }
            if (strlen($postal->getOrganisationName()))
            {
                $postalinfo->appendChild($this->createElement('contact:org',$postal->getOrganisationName()));
            }
            if ((($postal->getStreetCount())>0) || strlen($postal->getCity()) || strlen($postal->getProvince()) || strlen($postal->getZipcode()) || strlen($postal->getCountrycode()))
            {
                $postaladdr = $this->createElement('contact:addr');
                if (($count = $postal->getStreetCount())>0)
                {
                    for ($i = 0; $i < $count; $i++)
                    {
                        $postaladdr->appendChild($this->createElement('contact:street',$postal->getStreet($i)));
                    }
                }
                if (strlen($postal->getCity()))
                {
                    $postaladdr->appendChild($this->createElement('contact:city',$postal->getCity()));
                }
                if (strlen($postal->getProvince()))
                {
                    $postaladdr->appendChild($this->createElement('contact:sp',$postal->getProvince()));
                }
                if (strlen($postal->getZipcode()))
                {
                    $postaladdr->appendChild($this->createElement('contact:pc',$postal->getZipcode()));
                }
                if (strlen($postal->getCountrycode()))
                {
                    $postaladdr->appendChild($this->createElement('contact:cc',$postal->getCountrycode()));
                }
                $postalinfo->appendChild($postaladdr);
            }
            $element->appendChild($postalinfo);
        }
        if (strlen($contact->getVoice()))
        {
            $element->appendChild($this->createElement('contact:voice',$contact->getVoice()));
        }
        if (strlen($contact->getFax()))
        {
            $element->appendChild($this->createElement('contact:fax',$contact->getFax()));
        }
        if (strlen($contact->getEmail()))
        {
            $element->appendChild($this->createElement('contact:email',$contact->getEmail()));
        }
        if ($contact->getPassword())
        {
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw',$contact->getPassword()));
            $element->appendChild($authinfo);
        }
    }



    /**
     *
     * @param eppHost $hostInfo
     * @return domElement
     */
    public function updateHost($hostname, $addInfo, $removeInfo, $updateInfo)
    {
        #
        # Create command structure
        #
        $this->command = $this->createElement('command');
        #
        # Object create structure
        #
        $update = $this->createElement('update');
        $this->hostobject = $this->createElement('host:update');
        $this->hostobject->appendChild($this->createElement('host:name',$hostname));
        if ($addInfo instanceof eppHost)
        {
            $addcmd = $this->createElement('host:add');
            $this->addHostChanges($addcmd,$addInfo);
            $this->hostobject->appendChild($addcmd);
        }
        if ($removeInfo instanceof eppHost)
        {
            $remcmd = $this->createElement('host:rem');
            $this->addHostChanges($remcmd,$removeInfo);
            $this->hostobject->appendChild($remcmd);
        }
        if ($updateInfo instanceof eppHost)
        {
            // The update command command can only contain a hostname
            $chgcmd = $this->createElement('host:chg');
            if (strlen($updateInfo->getHostname())>0)
            {
                $chgcmd->appendChild($this->createElement('host:name',$updateInfo->getHostname()));
            }
            else
            {
                throw new eppException('New hostname must be specified on host:update command');
            }
            $this->hostobject->appendChild($chgcmd);
        }
        $update->appendChild($this->hostobject);
        $this->command->appendChild($update);
        $this->epp->appendChild($this->command);
    }

    /**
     *
     * @param domElement $element
     * @param eppHost $host
     */
    private function addHostChanges($element, eppHost $host)
    {
        $addresses = $host->getIpAddresses();
        if (is_array($addresses))
        {
            foreach ($addresses as $address=>$type)
            {
                $ipaddress = $this->createElement('host:addr',$address);
                $ipaddress->setAttribute('ip',$type);
                $element->appendChild($ipaddress);
            }
        }
        $statuses = $host->getHostStatuses();
        if (is_array($statuses))
        {
            foreach ($statuses as $status)
            {
                $stat = $this->createElement('host:status');
                $stat->setAttribute('s',$status);
                $element->appendChild($stat);
            }
        }
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