<?php
include_once(dirname(__FILE__).'/../../RegistrarInterfaceV2.php');
include_once(dirname(__FILE__).'/../../../../../../Modules/Tools/Main.php');
include_once(dirname(__FILE__).'/metaregEppConnection.php');
include_once(dirname(__FILE__).'/metaregEppLoginRequest.php');

class metaregInterface implements RegistrarInterfaceV2
{
    /**
     * Keeps the handle to an open connection
     * @var handle
     */
    private $conn;
    /**
     * State var to keep connected status
     * @var boolean
     */
    private $connected = false;
    /**
     * State var to keep loggedin status
     * @var boolean
     */
    private $loggedin = false;
    private $logintoken = null;
    
    public function __construct()
    {
        $this->conn = new metaregEppConnection();
    }
    
    public function __destruct()
    {
        if ($this->connected)
        {
            if ($this->loggedin)
            {                
                $this->logout();
                $this->loggedin = false;
            }
            $this->disconnect();
            $this->connected = false;
       }
    }
    
    /**
     * Connect to EPP server of the registry
     * @return boolean
     * @throws RegistrarException
     */
    public function connect()
    {
        $this->connected = false;
        try
        {            
            if ($this->conn->connect())
            {
                $this->connected = true;
                return true;
            }
            else
            {
                throw new RegistrarException('Connect failed');
            }
        }
        catch (eppException $e)
        {
            throw new RegistrarException($e->getMessage());
        }        
        return false;
    }
    
    /**
     * Disconnect from EPP service
     * @return boolean
     */
    public function disconnect()
    {
        if ($this->conn)
        {
            $this->conn->disconnect();
            $this->connected = false;
            $this->conn = new metaregEppConnection();
            return true;
        }
        return false;
    }
    
    public function setServer($server, $port=700)
    {
        $this->conn->setHostname($server);
    }

    public function setLoginToken($token)
    {
        $this->logintoken = $token;
    }

    /**
     * Send HELLO to EPP service
     * @return boolean
     * @throws RegistrarException
     */
    public function hello()
    {
        try
        {
            if ($this->connected)
            {
                $greeting = new eppHelloRequest();
                $response = $this->conn->writeandread($greeting);
                if (($response instanceof eppHelloResponse) && ($response->Success()))
                {
                    /* @var $response eppHelloResponse */
                    $this->conn->read();
                    return true;
                }
                else
                {
                    throw new RegistrarException($response->getResultMessage());
                }
            }
            else
            {
                throw new RegistrarException('Not connected during greeting');
            }
        }
        catch (eppException $e)
        {
            throw new RegistrarException($e->getMessage());
        }
        return false;
    }
    /**
     * Login to EPP service
     * @return boolean
     * @throws RegistrarException
     */
    public function login()
    {
        $this->loggedin = false;
        try
        {
            if ($this->connected)
            {
                $config = ConfigFactory::getConfigStore('MetaregistrarEPP');
                $this->conn->setUsername($config->username);
                if ($this->logintoken)
                {
                    $login = new metaregEppLoginRequest($this->logintoken);
                }
                else
                {
                    if (getenv('userid')>0)
                    {
                        $this->conn->setUsername(AuthV2Factory::getDefault()->getAccountUser()->name);
                        $login = new metaregEppLoginRequest(AuthV2Factory::getDefault()->createLoginToken());
                    }
                    else
                    {
                        $this->conn->setPassword($config->password);
                        $login = new metaregEppLoginRequest();
                    }
                }
                $response = $this->conn->writeandread($login);
                if (($response instanceof eppLoginResponse) && ($response->Success()))
                {
                    /* @var $response eppLoginResponse */
                    $this->loggedin = true;
                    return true;
                }
                else
                {
                    throw new RegistrarException($response->getResultMessage());
                }
            }
            else
            {
                throw new RegistrarException('Not connected during login');
            }
        }
        catch (eppException $e)
        {
            throw new RegistrarException($e->getMessage());
        }        
        return false;
    }

    /**
     * Logout from EPP service
     * @return boolean
     */
    public function logout()
    {
        try
        {
            if ($this->connected)
            {
                if ($this->loggedin)
                {
                    $logout = new eppLogoutRequest();
                    $response = $this->conn->writeandread($logout);
                    if (($response instanceof eppLogoutResponse) && ($response->Success()))
                    {
                        /* @var $response eppLogoutResponse */
                        return true;
                    }
                    else
                    {
                        throw new RegistrarException($response->getResultMessage());
                    }
                }
                else
                {
                    throw new RegistrarException('Loggedin but not connected during logout');
                }
            }
        }
        catch (eppException $e)
        {
            throw new RegistrarException($e->getMessage());
        }        
        return false;
    }       

    /**
     * Check availability of one or more domainnames
     * @param array $domainnames
     */
	public function checkDomains($domainnames)
	{
        if (($this->connected) && ($this->loggedin))
        {
            foreach ($domainnames as $domainname)
            {
                $domain[] = new eppDomain($domainname);
            }
            $check = new eppCheckRequest($domain);
            $response = $this->conn->writeandread($check);
            if (($response instanceof eppCheckResponse) && ($response->Success()))
            {
                /* @var $response eppCheckResponse */
                $result = new riCheckDomainResponse();
                $result->setResultCode($response->getResultCode());
                $domains = $response->getCheckedDomains();
                foreach ($domains as $domain)
                {
                    $result->addCheckedDomain($domain['domainname'], $domain['available'],$domain['reason']);
                }      
                return $result;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during domaincheck');
        }
	}

    
    /**
     * Retrieve information on a domain object
     * 
     * @param string $domainname
     * @return array
     * @throws RegistrarException
     */
    public function infoDomain($domainname)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $domain = new eppDomain($domainname);
            $info = new eppInfoDomainRequest($domain);
            $response = $this->conn->writeandread($info);
            if (($response instanceof eppInfoDomainResponse) && ($response->Success()))
            {
                /* @var $response eppInfoDomainResponse */
                $dom = new riInfoDomainResponse();
                $dom->setResultCode($response->getResultCode());
                $dom->setDomainname($response->getDomainName());
                $dom->setRoid($response->getDomainRoid());
                $dom->setRegistrant($response->getDomainRegistrant());                
                $dom->setClId($response->getDomainClientId());
                $dom->setCrId($response->getDomainCreateClientId());
                $dom->setCrDate($response->getDomainCreateDate());
                $dom->setExDate($response->getDomainExpirationDate());                
                $dom->setUpDate($response->getDomainUpdateDate());
                $dom->setUpId($response->getDomainUpdateClientId());
                $dom->setAuthcode($response->getDomainAuthInfo());
                $nameservers = $response->getDomainNameservers();
                if (is_array($nameservers))
                {
                    foreach ($nameservers as $ns)
                    {
                        $dom->addNameserver($ns->getHostname());
                    }
                }
                $hosts = $response->getDomainHosts();
                if (is_array($hosts))
                {
                    foreach ($hosts as $host)
                    {
                        $dom->addHost($host->getHostname());
                    }
                }
                $contacts = $response->getDomainContacts();
                if (is_array($contacts))
                {
                    foreach ($contacts as $contact)
                    {
                        $c = new riContactHandle($contact->getContactHandle(),$contact->getContactType());
                        $dom->addContact($c);
                    }
                }
                $statuses = $response->getDomainStatusCSV();
                if (strlen($statuses)>0)
                {
                    $statuses = explode(',',$statuses);
                    foreach ($statuses as $status)
                    {
                        $dom->addStatus($status);
                    }
                }
                return $dom;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during infodomain');
        }
    }    

    public function createDomain($domainname, $ownercontact, $contacts, $hosts, $authinfo=null, $period=null)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $registrant = new eppContactHandle($ownercontact);
            foreach ($contacts as $contacttype=>$contactid)
            {
                $c[] = new eppContactHandle($contactid,$contacttype);
            }
            foreach ($hosts as $host)
            {
                $h[] = new eppHost($host);
            }
            if (!$authinfo)
            {
                $password = Tools::GeneratePassword(false,null,12);
                $authinfo = $password[0];
            }
            $domain = new eppDomain($domainname,$registrant,$c,$h,1, $authinfo);
            if ($period)
            {
                $domain->setPeriodUnit($period[1]);
                $domain->setPeriod($period[0]);
            }
            $create = new eppCreateRequest($domain);
            $response = $this->conn->writeandread($create);
            if (($response instanceof eppCreateResponse) && ($response->Success()))
            {
                /* @var $response eppCreateResponse */
                return new riCreateDomainResponse($response->getDomainName(), $response->getDomainCreateDate(), $response->getDomainExpirationDate());
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }                
        }
        else
        {
            throw new RegistrarException('Not connected during domaincreate');
        }                    
    }

    public function renewDomain($domainname, $expdate=null, $period=null)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $domain = new eppDomain($domainname);
            if ($period)
            {
                $domain->setPeriodUnit($period[1]);
                $domain->setPeriod($period[0]);
            }
            $renew = new eppRenewRequest($domain, $expdate);
            $response = $this->conn->writeandread($renew);
            if (($response instanceof eppRenewResponse) && ($response->Success()))
            {
                /* @var $response eppRenewResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during domainrenew');
        }
    }



    /**
     * Delete domain object
     * 
     * @param string $domainname
     * @return boolean
     * @throws RegistrarException
     */
    public function deleteDomain($domainname)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $domain = new eppDomain($domainname);
            $delete = new eppDeleteRequest($domain);
            $response = $this->conn->writeandread($delete);
            if (($response instanceof eppDeleteResponse) && ($response->Success()))
            {
                /* @var $response eppDeleteResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during domaindelete');
        }            
    }    

    


    /**
     * Transfer domain object
     *
     * @param string $domainname
     * @param string $authcode
     * @return boolean
     * @throws RegistrarException
     */
    public function transferDomain($domainname, $authcode)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $domain = new eppDomain($domainname,null,null,null,0,$authcode);
            $transfer = new eppTransferRequest(eppTransferRequest::OPERATION_REQUEST, $domain);
            $response = $this->conn->writeandread($transfer);
            if (($response instanceof eppTransferResponse) && ($response->Success()))
            {
                /* @var $response eppTransferResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during domaintransfer');
        }
    }


    /**
     * Transfer contact object
     *
     * @param string $domainname
     * @param string $authcode
     * @return boolean
     * @throws RegistrarException
     */
    public function transferContact($contactid, $authcode)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $contact = new eppContactHandle($contactid);
            $transfer = new eppTransferRequest(eppTransferRequest::OPERATION_REQUEST, $contact);
            $response = $this->conn->writeandread($transfer);
            if (($response instanceof eppTransferResponse) && ($response->Success()))
            {
                /* @var $response eppTransferResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during contacttransfer');
        }
    }

    
    
    public function infoHost($hostname)
    {
        $result = null;
        if (($this->connected) && ($this->loggedin))
        {
            $host = new eppHost($hostname);            
            $info = new eppInfoHostRequest($host);
            $response = $this->conn->writeandread($info);
            if (($response instanceof eppInfoHostResponse) && ($response->Success()))
            {
                /* @var $response eppInfoHostResponse */
                $name = $response->getHostName();
                $result[$name]['roid']=$response->getHostRoid();
                $result[$name]['status']=$response->getHostStatus();
                $result[$name]['address']=$response->getHostAddresses();
                $result[$name]['createdate']=$response->getHostCreateDate();
                $result[$name]['updatedate']=$response->getHostUpdateDate();
                $result[$name]['createdate']=$response->getHostCreateDate();
                $result[$name]['clientid']=$response->getHostClientId();
                $result[$name]['createclientid']=$response->getHostCreateClientId();
                $result[$name]['updateclientid']=$response->getHostUpdateClientId();
                return $result;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during infohost');
        }            
    }
    
    
    
    
    /**
     * Check existence of multiple contact objects
     * 
     * @param array $contacts
     * @return boolean
     * @throws RegistrarException
     */
    public function checkHosts($hosts)
    {
        if (($this->connected) && ($this->loggedin))
        {
            foreach ($hosts as $host)
            {
                $handles[] = new eppHost($host);
            }
            $check = new eppCheckRequest($handles);
            $response = $this->conn->writeandread($check);
            if (($response instanceof eppCheckResponse) && ($response->Success()))
            {
                /* @var $response eppCheckResponse */
                $result = new riCheckHostResponse();
                $result->setResultCode($response->getResultCode());
                $hosts = $response->getCheckedHosts();
                foreach ($hosts as $hostname=>$available)
                {
                    $result->addCheckedHost($hostname, $available);
                }
                return $result;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during contactdelete');
        }            
    }
    
    
    /**
     * Create host object with attached IP address
     * 
     * @param string $hostname
     * @param array $ipaddress
     * @return array
     * @throws RegistrarException
     */
    public function createHost($hostname, $ipaddresses)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $host = new eppHost($hostname, $ipaddresses);            
            return $this->createHostCommand($host);
        }
        else
        {
            throw new RegistrarException('Not connected during hostcreate');
        }            
    }
    
    /**
     * Internal command to create host object
     * 
     * @param string $host
     * @return array
     * @throws RegistrarException
     */
    private function createHostCommand($host)
    {
        $create = new eppCreateRequest($host);
        $response = $this->conn->writeandread($create);
        if (($response instanceof eppCreateResponse) && ($response->Success()))
        {
            /* @var $response eppCreateResponse */
            $created[$response->getHostname()]=$response->getHostCreateDate();
            return $created;
        }
        else
        {
            throw new RegistrarException($response->getResultMessage());                    
        }
    }
    
    /**
     * Delete host object
     * 
     * @param string $hostname
     * @return boolean
     * @throws RegistrarException
     */
    public function deleteHost($hostname)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $host = new eppHost($hostname);
            $delete = new eppDeleteRequest($host);
            $response = $this->conn->writeandread($delete);
            if (($response instanceof eppDeleteResponse) && ($response->Success()))
            {
                /* @var $response eppDeleteResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during hostdelete');
        }            
    }
       
    
    
    public function updateHost($hostname, $addinfo, $removeinfo, $updateinfo)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $host = new eppHost($hostname);
            $update = new eppUpdateRequest($host, $addinfo, $removeinfo, $updateinfo);
            $response = $this->conn->writeandread($update);
            if (($response instanceof eppUpdateResponse) && ($response->Success()))
            {
                /* @var $response eppUpdateResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during hostupdate');
        }       
    }
	
	/**
	 * Upgrade domain object
	 * 
	 * @param string $domainname
	 * @param array $addinfo
	 * @param array $removeinfo
	 * @param array $updateinfo
	 * @return boolean
	 * @throws RegistrarException 
	 */
	public function updateDomain(riDomain $domain)
    {
        $adds = 0;
        $upds = 0;
        $rems = 0;
        $addinfo = new eppDomain($domain->getDomainname());
        $removeinfo = new eppDomain($domain->getDomainname());
        $updateinfo = new eppDomain($domain->getDomainname());
        /*
         * Retrieve information on domain name
         * Remove all stuff
         * Update owner id
         * Update Authinfo
         *
         * Add or remove nameservers
         * Add or remove contact info
         * Add or remove statuses
         */

        $info = $this->infoDomain($domain->getDomainname());
        if ($info instanceof riInfoDomainResponse)
        {
            /* @var $info riInfoDomainResponse */
            // Determine if the registrant of the domain name has changed
            if (strlen($domain->getRegistrant())>0)
            {
                if ($info->getRegistrant()!=$domain->getRegistrant())
                {
                    $upds++;
                    $updateinfo->setRegistrant($domain->getRegistrant());
                }
            }
            // Determine if the auth code of the domain name has changed
            if (strlen($domain->getAuthorisationCode())>0)
            {
                if ($info->getAuthcode()!=$domain->getAuthorisationCode())
                {
                    $upds++;
                    $updateinfo->setAuthorisationCode($domain->getAuthorisationCode());
                }
            }
            /**
             * Determine if the contacts of the domain name have changed, add and remove where necessary
             */
            $contacts = $info->getContacts();
            $domcontacts = $domain->getContacts();
            if ((is_array($domcontacts)) && (count($domcontacts)>0))
            {
                if (is_array($contacts))
                {
                    foreach ($contacts as $contact)
                    {
                        $eppcontactfound = false;
                        /* @var $contact eppContactHandle */
                        foreach($domcontacts as $domcontact)
                        {
                            /* @var $domcontact riContactHandle */
                           if (($domcontact->getContactHandle() == $contact->getContactHandle()) && ($domcontact->getContactType()==$contact->getContactType()))
                           {
                               // Contact found, nothing to change
                               $eppcontactfound = true;
                           }
                        }
                        // Contact was not found in updated domain, so delete it
                        if (!$eppcontactfound)
                        {
                            $c = new eppContactHandle($contact->getContactHandle(),$contact->getContactType());
                            $removeinfo->addContact($c);
                            $rems++;
                        }
                    }                   
                }
                // Determine which domcontacts were not processed so far and so must be added to the domain                
                foreach($domcontacts as $domcontact)
                {
                    $domcontactfound = false;
                    /* @var $domcontact riContactHandle */
                    if (is_array($contacts))
                    {                        
                        foreach ($contacts as $contact)
                        {
                            if (($contact->getContactHandle()==$domcontact->getContactHandle()) && ($contact->getContactType()==$domcontact->getContactType()))
                            {
                                $domcontactfound = true;
                            }
                        }
                    }
                    if (!$domcontactfound)
                    {
                        $c = new eppContactHandle($domcontact->getContactHandle(),$domcontact->getContactType());
                        $addinfo->addContact($c);
                        $adds++;
                    }
                }
            }
            /**
             * Determine if the nameservers of the domain name have changed, add and remove where necessary
             */

            $hosts = $info->getNameservers();
            $domhosts = $domain->getHosts();
            if ((is_array($domhosts)) && (count($domhosts)>0))
            {
                if (is_array($hosts))
                {
                    foreach ($hosts as $host)
                    {
                        $epphostfound = false;
                        foreach($domhosts as $domhost)
                        {
                            /* @var $domhost riHost */
                           if ($domhost->getHostname() == $host)
                           {
                               // Host found, nothing to change
                               $epphostfound = true;
                           }
                        }
                        // Host was not found in updated domain, so delete it
                        if (!$epphostfound)
                        {
                            $h = new eppHost($host);
                            $removeinfo->addHost($h);
                            $rems++;
                        }
                    }
                }
                // Determine which domcontacts were not processed so far and so must be added to the domain
                foreach($domhosts as $domhost)
                {
                    $domhostfound = false;
                    /* @var $domhost riHost */
                    if (is_array($hosts))
                    {
                        foreach ($hosts as $host)
                        {
                            if ($host==$domhost->getHostname())
                            {
                                $domhostfound = true;
                            }
                        }
                    }
                    if (!$domhostfound)
                    {
                        $h = new eppHost($domhost->getHostname());
                        $addinfo->addHost($h);
                        $adds++;
                    }
                }
            }
            /**
             * Determine if the statuses of the domain name have changed, add and remove where necessary
             */
            $statuses = $info->getStatuses();
            $domstatuses = $domain->getStatuses();
            if ((is_array($domstatuses)) && (count($domstatuses)>0))
            {
                if (is_array($statuses))
                {
                    foreach ($statuses as $status)
                    {
                        $eppstatusfound = false;
                        foreach($domstatuses as $domstatus)
                        {
                           if ($domstatus == $status)
                           {
                               // Status found, nothing to change
                               $eppstatusfound = true;
                           }
                        }
                        // Status was not found in updated domain, so delete it
                        if ((!$eppstatusfound) && ($status!=eppDomain::STATUS_OK))
                        {
                            $removeinfo->addStatus($status);
                            $rems++;
                        }
                    }
                }
                // Determine which domstatuses were not processed so far and so must be added to the domain
                foreach ($domstatuses as $domstatus)
                {
                    if (is_array($statuses))
                    {
                        $domstatusfound = false;
                        foreach ($statuses as $status)
                        {
                            if ($status==$domstatus)
                            {
                                $domstatusfound = true;
                            }
                        }
                        if (!$domstatusfound)
                        {
                            $addinfo->addStatus($domstatus);
                            $adds++;
                        }
                    }
                }
            }
        }

        if (($this->connected) && ($this->loggedin))
        {
            $domain = new eppDomain($domain->getDomainname());
            if ($adds == 0) $addinfo = null;
            if ($rems == 0) $removeinfo = null;
            if ($upds == 0) $updateinfo = null;
            if (($adds == 0) && ($rems == 0) && ($upds == 0))
            {
                throw new RegistrarException('Nothing to update on the updateDomain command');
            }                        
            $update = new eppUpdateRequest($domain, $addinfo, $removeinfo, $updateinfo);
            $response = $this->conn->writeandread($update);
            if (($response instanceof eppUpdateResponse) && ($response->Success()))
            {
                /* @var $response eppUpdateResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during domainupdate');
        }       
    }
	
	public function changeDomainStatus($domainname, $newStatus)
	{
        if (($this->connected) && ($this->loggedin))
        {
			$domain = new eppDomain($domainname);
			$addinfo = clone $domain;
			$removeinfo = clone $domain;
			$updateinfo = clone $domain;
			
			$updateinfo->addStatus($newStatus);
			
            $update = new eppUpdateRequest($domain, $addinfo, $removeinfo, $updateinfo);
            $response = $this->conn->writeandread($update);
            if (($response instanceof eppUpdateResponse) && ($response->Success()))
            {
                /* @var $response eppUpdateResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during domainupdate');
        }  
	}

	/**
	 * Update contact object
	 * 
	 * @param integer $contactid
	 * @param riContact $contactinfo
	 * @return boolean
	 * @throws RegistrarException 
	 */
    
    public function updateContact($contactid, riContact $contactinfo)
    {
        $currentcontact = $this->infocontact($contactid);
        /* @var $currentcontact riInfoContactResponse */
        $addinfo = new eppContact;
        $removeinfo = new eppContact;
        $updateinfo = new eppContact;
        $adds = 0;
        $rems = 0;
        $upds = 0;
        if (($currentcontact->getEmail() != $contactinfo->getEmail()) && ($contactinfo->getEmail()!=null))
        {
            $updateinfo->setEmail($contactinfo->getEmail());
            $upds++;
        }
        if (($currentcontact->getVoice() != $contactinfo->getVoice()) && ($contactinfo->getVoice()!=null))
        {
            $updateinfo->setVoice($contactinfo->getVoice());
            $upds++;
        }
        if (($currentcontact->getFax() != $contactinfo->getFax()) && ($contactinfo->getFax()!=null))
        {
            $updateinfo->setFax($contactinfo->getFax());
            $upds++;
        }
        $cpi = $currentcontact->getPostalInfo();
        $currentpostalinfo = $cpi[0];
        /* @var $currentpostalinfo riInfoContactPostalInfo */
        if ($contactinfo->getPostalInfoLength() > 0)
        {
            $updatecpi = new eppContactPostalInfo();
            $updateinfo->addPostalInfo($updatecpi);
            $contactpostalinfo = $contactinfo->getPostalInfo(0);
            $updatecpi->setName($contactpostalinfo->getName());
            $updatecpi->setCity($contactpostalinfo->getCity());
			$updatecpi->setOrganisationName($contactpostalinfo->getOrganisationName());
			$updatecpi->setZipcode($contactpostalinfo->getZipcode());
			$updatecpi->addStreet($contactpostalinfo->getStreet(0));
			$updatecpi->setProvince($contactpostalinfo->getProvince());
			$updatecpi->setCountrycode($contactpostalinfo->getCountrycode());
			
			$upds++;

        }
        if ($contactinfo->getStatus()!=null)
        {
            $statuses = $currentcontact->getStatus();
            foreach ($statuses as $status)
            {
                $statusfound = false;
                if ($status != 'ok')
                {
                    if ($status == $contactinfo->getStatus())
                    {
                        $statusfound = true;
                    }
                    else
                    {
                        $removeinfo->setStatus($status);
                        $rems++;
                    }
                }
            }
            if (!$statusfound)
            {
                foreach ($contactinfo->getStatus() as $contactstatus)
                {
                    if ($contactstatus!=riContact::STATUS_OK)
                    {
                        $addinfo->setStatus($contactstatus);
                        $adds++;
                    }
                }
            }
        }
        if ($adds == 0) $addinfo = null;
        if ($rems == 0) $removeinfo = null;
        if ($upds == 0) $updateinfo = null;
        if (($this->connected) && ($this->loggedin))
        {
            $update = new eppUpdateRequest($contactid, $addinfo, $removeinfo, $updateinfo);
            $response = $this->conn->writeandread($update);
            if (($response instanceof eppUpdateResponse) && ($response->Success()))
            {
                /* @var $response eppUpdateResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());
            }
        }
        else
        {
            throw new RegistrarException('Not connected during domainupdate');
        }       
    }
    
    
    /**
     * Information about a specific contact
     * 
     * @param string $contactid
     * @return array
     * @throws RegistrarException
     */
    public function infoContact($contactid)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $contact = new eppContactHandle($contactid);
            $info = new eppInfoContactRequest($contact);
            $response = $this->conn->writeandread($info);
            if (($response instanceof eppInfoContactResponse) && ($response->Success()))
            {
                /* @var $response eppInfoContactResponse */
                $contact = new riInfoContactResponse();
                $contact->setResultCode($response->getResultCode());
                $contact->setId($response->getContactId());
                $contact->setRoid($response->getContactRoid());
                $statuses = $response->getContactStatus();
                foreach ($statuses as $status)
                {
                    $contact->addStatus($status);
                }
                $contact->setEmail($response->getContactEmail());
                $contact->setVoice($response->getContactVoice());
                $contact->setFax($response->getContactFax());
                $contact->setClId($response->getContactClientId());
                $contact->setCrId($response->getContactCreateClientId());
                $contact->setCrDate($response->getContactCreateDate());
                $contact->setUpId($response->getContactUpdateClientId());
                $contact->setUpDate($response->getContactUpdateDate());
                $postalinfos = $response->getContactPostalInfo();
                foreach ($postalinfos as $postal)
                {
                    $contact->addPostalInfo($postal->getName(), $postal->getOrganisationName(), $postal->getStreets(), $postal->getCity(), $postal->getCountrycode(), $postal->getZipcode(), $postal->getProvince(), $postal->getType());
                }
                return $contact;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during infocontact');
        }                
    }
    

    /**
     * Check existence of multiple contact objects
     * 
     * @param array $contacts
     * @return boolean
     * @throws RegistrarException
     */
    public function checkContacts($contacts)
    {
        if (($this->connected) && ($this->loggedin))
        {
            foreach ($contacts as $contact)
            {
                $handles[] = new eppContactHandle($contact);
            }
            $check = new eppCheckRequest($handles);
            $response = $this->conn->writeandread($check);
            if (($response instanceof eppCheckResponse) && ($response->Success()))
            {
                /* @var $response eppCheckResponse */
                $contacts = $response->getCheckedContacts();
                $result = new riCheckContactResponse();
                foreach ($contacts as $contactid=>$available)
                {
                    $result->addCheckedContact($contactid, $available);
                }
                return $result;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during contactdelete');
        }            
    }     
    
    
    /**
     * Create contact object with registry
     * 
     * @param string $hostname
     * @return array
     * @throws RegistrarException
     */
    public function createContact(riContact $contact)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $pi = $contact->getPostalInfo(0);
            /* @var $pi riContactPostalInfo */
            $postalinfo = new eppContactPostalInfo($pi->getName(), $pi->getCity(), $pi->getCountrycode(), $pi->getOrganisationName(), $pi->getStreets(), $pi->getProvince(), $pi->getZipcode());
            $contact = new eppContact($postalinfo, $contact->getEmail(), $contact->getVoice(), $contact->getFax());
            $create = new eppCreateRequest($contact);
            $response = $this->conn->writeandread($create);
            if (($response instanceof eppCreateResponse) && ($response->Success()))
            {
                /* @var $response eppCreateResponse */
                return new riCreateContactResponse($response->getContactId(),$response->getContactCreateDate());
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during contactcreate');
        }            
    }    
    
    
    /**
     * Delete contact object
     * 
     * @param string $contactid
     * @return boolean
     * @throws RegistrarException
     */
    public function deleteContact($contactid)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $contact = new eppContactHandle($contactid);
            $delete = new eppDeleteRequest($contact);
            $response = $this->conn->writeandread($delete);
            if (($response instanceof eppDeleteResponse) && ($response->Success()))
            {
                /* @var $response eppDeleteResponse */
                if ($response->getResultCode()==1000)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during contactdelete');
        }            
    }    
    
    public function getPollMessageCount()
    {
        if (($this->connected) && ($this->loggedin))
        {
            $poll = new eppPollRequest(eppPollRequest::POLL_REQ);
            $response = $this->conn->writeandread($poll);
            if (($response instanceof eppPollResponse) && ($response->Success()))
            {
                /* @var $response eppPollResponse */
                return $response->getMessageCount();                    
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during getpollmessage');
        }            
    }

    
    public function getNextPollMessage($messageid = null)
    {
        $result = null;
        if (($this->connected) && ($this->loggedin))
        {
            $poll = new eppPollRequest(eppPollRequest::POLL_REQ, $messageid);
            $response = $this->conn->writeandread($poll);
            if (($response instanceof eppPollResponse) && ($response->Success()))
            {
                /* @var $response eppPollResponse */
                $result['count'] = $response->getMessageCount();                    
                $result['id']=$response->getMessageId();
                $result['date']=$response->getMessageDate();
                $result['message']=$response->getMessage();
                return $result;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during getpollmessage');
        }            
    }
    
    public function acknowledgePollMessage($messageid)
    {
        if (($this->connected) && ($this->loggedin))
        {
            $poll = new eppPollRequest(eppPollRequest::POLL_ACK, $messageid);
            $response = $this->conn->writeandread($poll);
            if (($response instanceof eppPollResponse) && ($response->Success()))
            {
                /* @var $response eppPollResponse */
                return true;
            }
            else
            {
                throw new RegistrarException($response->getResultMessage());                    
            }
        }
        else
        {
            throw new RegistrarException('Not connected during getpollmessage');
        }            

    }
    
}
