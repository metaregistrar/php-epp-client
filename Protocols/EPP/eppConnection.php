<?php

class EppConnection
{
    /**
     * Hostname of this connection
     * @var string
     */
    protected $hostname = '';
    
    /**
     * Port of the connection
     * @var string
     */
    protected $port = 700;

    /**
     * Time-out value for the server connection
     * @var integer
     */
    protected $timeout = 5;
    
    /**
     * Username to be used in the connection
     * @var string
     */
    protected $username = '';
    
    /**
     * Password to be used in the connection
     * @var string
     */
    protected $password = '';

    /*
     * New password for password change procedure
     */
    protected $newpassword = null;


   /**
     * Default namespace
     * @var string
     */
    protected $defaultnamespace = array('xmlns'=>'urn:ietf:params:xml:ns:epp-1.0');
            
    /**
     * Base objects
     * @var array of accepted object URI's
     */
    protected $objuri = array('urn:ietf:params:xml:ns:domain-1.0'=>'domain','urn:ietf:params:xml:ns:contact-1.0'=>'contact','urn:ietf:params:xml:ns:host-1.0'=>'host');

    /**
     * Object extensions
     * @var array of accepted URI's for each object
     */
    protected $exturi;

    /**
     * Base objects
     * @var array of accepted URI's for xpath
     */
    protected $xpathuri = array('urn:ietf:params:xml:ns:epp-1.0'=>'epp','urn:ietf:params:xml:ns:domain-1.0'=>'domain','urn:ietf:params:xml:ns:contact-1.0'=>'contact','urn:ietf:params:xml:ns:host-1.0'=>'host');

    /**
     * These namespaces are needed in the root of the EPP object
     * @var type array of accepted URI's for xpath
     */
    protected $rootspace = array();

    /**
     *
     * @var string language for epp
     */
    protected $language = '';
    
    /**
     *
     * @var string version for epp
     */
    protected $version = '';
    
    /**
     *
     * @var resource $connection
     */
    protected $connection;

    /**
     *
     * @var boolean $logging
     */
    protected $logging;
    
    /**
     * Commands and equivalent responses
     * @var array
     */
    protected $responses;

    /**
     * Path to certificate file
     * @var string
     */
    protected $local_cert_path = null;

    /**
     * Password of certificate file
     * @var string
     */
    protected $local_cert_pwd = null;
    
    function __construct($logging = false)
    {
        if ($logging)
        {
            $this->enableLogging();
        }
        #
        # Initialize default values for config parameters
        #
        
        $this->language = 'en';
        $this->version = '1.0';
        $this->responses['eppHelloRequest'] = 'eppHelloResponse';
        $this->responses['eppLoginRequest'] = 'eppLoginResponse';
        $this->responses['eppLogoutRequest'] = 'eppLogoutResponse';
        $this->responses['eppPollRequest'] = 'eppPollResponse';
        $this->responses['eppCheckRequest'] = 'eppCheckResponse';
        $this->responses['eppInfoHostRequest'] = 'eppInfoHostResponse';
        $this->responses['eppInfoContactRequest'] = 'eppInfoContactResponse';
        $this->responses['eppInfoDomainRequest'] = 'eppInfoDomainResponse';
        $this->responses['eppCreateRequest'] = 'eppCreateResponse';
        $this->responses['eppDeleteRequest'] = 'eppDeleteResponse';
        $this->responses['eppUpdateRequest'] = 'eppUpdateResponse';
        $this->responses['eppRenewRequest'] = 'eppRenewResponse';
        $this->responses['eppTransferRequest'] = 'eppTransferResponse';
        
    }

    function __destruct()
    {
        if ($this->logging)
        {
            $this->showLog();
        }
    }
    
    
    
    public function enableDnssec()
    {
        $this->exturi['urn:ietf:params:xml:ns:secDNS-1.1'] = 'secDNS';
        $this->responses['eppDnssecInfoDomainRequest'] = 'eppDnssecInfoDomainResponse';
        $this->responses['eppDnssecUpdateRequest'] = 'eppUpdateResponse';
    }
    
    public function disableDnssec()
    {
        unset($this->exturi['secDNS']);
        unset($this->responses['eppDnssecInfoDomainRequest']);
        unset($this->responses['eppDnssecUpdateRequest']);
    }

    public function enableCertification($certificatepath, $certificatepassword)
    {
        $this->local_cert_path= $certificatepath;
        $this->local_cert_pwd = $certificatepassword;
    }
    
    public function disableCertification()
    {
        $this->local_cert_path= null;
        $this->local_cert_pwd = null;
    }


    /**
     * Disconnects if connected
     * @return boolean
     */
    public   function disconnect()
    {
        if (is_resource($this->connection))
        {
            fclose($this->connection);
        }
        return true;
    }
    /**
     * Connect to the address and port
     * @param string $address
     * @param int $port
     * @return boolean
     */
    public function connect($hostname = null, $port =null)
    {
        if ($hostname)
        {
            $this->hostname = $hostname;
        }
        if ($port)
        {
            $this->port = $port;
        }
        if ($this->local_cert_path)
        {
            $ssl = true;
            $target = sprintf('%s://%s:%d', ($ssl === true ? 'ssl' : 'tcp'), $this->hostname, $this->port);
            $errno='';
            $errstr='';
            $context = stream_context_create();
            stream_context_set_option($context, 'ssl', 'local_cert', $this->local_cert_path);
            stream_context_set_option($context, 'ssl', 'passphrase', $this->local_cert_pwd);
            if ($this->connection = @stream_socket_client($target, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context))
            {
                $this->writeLog("Connection made");
                $this->read();
                return true;
            }
            else
            {
                throw new eppException("Error connecting to $target: $errstr (code $errno)");
            }
        }
        else
        {
             //We don't want our error handler to kick in at this point...
            putenv('SURPRESS_ERROR_HANDLER=1');
            #echo "Connecting: $this->hostname:$this->port\n";
            $this->writeLog("Connecting: $this->hostname:$this->port");
            $this->connection = fsockopen($this->hostname, $this->port, $errno, $errstr, $this->timeout);
            putenv('SURPRESS_ERROR_HANDLER=0');
            if (is_resource($this->connection))
            {
                $this->writeLog("Connection made");
                stream_set_blocking($this->connection, false);
                stream_set_timeout($this->connection,$this->timeout);
                if ($errno == 0)
                {
                    $this->read();
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $this->writeLog("Connection could not be opened: $errno $errstr");
                return false;
            }
        }
    }

    
    /**
     * This will read 1 response from the connection
     * @return string
     */
    public function read()
    {
        putenv('SURPRESS_ERROR_HANDLER=1');
        $content = '';
        $time = time()+$this->timeout;

        while ((!isset ($length)) || ($length > 0))
        {
            if (feof($this->connection))
            {
                putenv('SURPRESS_ERROR_HANDLER=0');
                throw new eppException ('Unexpected closed connection by remote host...');
            }
            //Check if timeout occured
            if (time() >= $time)
            {
                putenv('SURPRESS_ERROR_HANDLER=0');
                return false;
            }
            //If we dont know how much to read we read the first few bytes first, these contain the content-lenght
            //of whats to come
            if ((!isset($length)) || ($length == 0))
            {
				$readLength = 4;
				$readbuffer = "";
				$read = "";
				while ($readLength > 0)
				{
					if ($readbuffer = fread($this->connection, $readLength))
					{					
						$readLength = $readLength - strlen($readbuffer);
						$read .= $readbuffer;
					}
					//Check if timeout occured
					if (time() >= $time)
					{
						putenv('SURPRESS_ERROR_HANDLER=0');
						return false;
					}
				}
				$this->writeLog("Reading 4 bytes for integer. (read: ".strlen($read).")");
				$this->writeLog($read);
				$length = $this->readInteger($read)-4;
				$this->writeLog("Reading next: $length bytes");
            }
            //We know the length of what to read, so lets read the stuff
            if ((isset($length)) && ($length > 0))
            {
                $time = time()+$this->timeout;
                $this->writeLog("Reading $length bytes of content.");
                if ($read = fread($this->connection, $length))
                {
                    $this->writeLog($read);
                    $this->writeLog(print_R(socket_get_status($this->connection), true));
                    $length = $length-strlen($read);
                    $content .= $read;
                }
            }
			if (!strlen($read))
			{
				usleep(100);
			}
        }
        putenv('SURPRESS_ERROR_HANDLER=0');
        return $content;
    }

    /**
     * This parses the first 4 bytes into an integer for use to compare content-length
     *
     * @param string $content
     * @return integer
     */
    private function readInteger($content)
    {
		$int = unpack('N', substr($content, 0, 4));		
        return $int[1];
    }

    /**
     * This adds the content-length to the content that is about to be written over the EPP Protocol
     *
     * @param string $content Your XML
     * @return string String to write
     */
    private function addInteger($content)
    {
        $int = pack('N',intval(strlen($content)+4));
        return $int.$content;
    }
    /**
     * Write stuff over the EPP connection
     * @param string $content
     * @return boolean
     */
    private function write($content)
    {
        $this->writeLog("Writing: ".strlen($content)." + 4 bytes");
        $content = $this->addInteger($content);
        if (!is_resource($this->connection))
        {
            throw new eppException ('Writing while no connection is made is not supported.');
        }
        $this->writeLog($content);
        putenv('SURPRESS_ERROR_HANDLER=1');
        if (fwrite($this->connection, $content))
        {
            //fpassthru($this->connection);
            putenv('SURPRESS_ERROR_HANDLER=0');
            return true;
        }
        putenv('SURPRESS_ERROR_HANDLER=0');
        return false;
    }

    /**
     * Write the content domDocument to the stream
     * Read the answer
     * Load the answer in a response domDocument
     * return the reponse
     *  
     * @param domDocument $content
     * @return domDocument
     * @throws eppException 
     */    
    public function writeandread($content)
    {
        $requestsessionid = $content->getSessionId();
        $namespaces = $this->getDefaultNamespaces();
        if (is_array($namespaces))
        {
            foreach ($namespaces as $id=>$namespace)
            {
                $content->addExtension($id,$namespace);
            }
        }
        /*
         * $content->login is only set if this is an instance or a sub-instance of an eppLoginRequest
         */
        if ($content->login)
        {
            // Set username for login request
            $content->addUsername($this->getUsername());
            // Set password for login request
            $content->addPassword($this->getPassword());
            // Set 'new password' for login request
            if ($this->getNewPassword())
            {
                $content->addNewPassword($this->getNewPassword());
            }
            // Add version to this object
            $content->addVersion($this->getVersion());
            // Add language to this object
            $content->addLanguage($this->getLanguage());
            // Add services and extensions to this content
            $content->addServices($this->getServices(),$this->getExtensions());
        }
        /*
         * $content->hello is only set if this is an instance or a sub-instance of an eppHelloRequest
         */
        if (!($content->hello))
        {   
            /**
             * Add used namespaces to the correct places in the XML 
             */
            $content->addNamespaces($this->getServices());
            $content->addNamespaces($this->getExtensions());
        }
        $response = $this->createResponse($content);
        if ($this->logging)
        {
            $this->writeLog("==== SENDING XML ======");
            $this->writeLog($content->saveXML(null, LIBXML_NOEMPTYTAG));
        }
        if ($this->write($content->saveXML(null, LIBXML_NOEMPTYTAG)))
        {
            $xml = $this->read();
            if (strlen($xml))
            {                
                if ($response->loadXML($xml))
                {
                    if ($this->logging)
                    {
                        $this->writeLog("==== RECEIVED XML =====");
                        $this->writeLog($response->saveXML(null, LIBXML_NOEMPTYTAG));
                    }
                    $clienttransid = $response->getClientTransactionId();                    
                    if (($clienttransid) && ($clienttransid != $requestsessionid))
                    {
                        throw new eppException("Client transaction id $requestsessionid does not matched returned $clienttransid");
                    }
                    $response->setXpath($this->getServices());
                    $response->setXpath($this->getExtensions());
                    $response->setXpath($this->getXpathExtensions());
                    if ($response instanceof eppHelloResponse)
                    {            
                        $response->validateServices($this->getLanguage(),$this->getVersion(),$this->getServices(),$this->getExtensions());
                    }
                    return $response;
                }
            }
            else
            {
                throw new eppException('Empty XML document when receiving data!');
            }
        }
        else
        {
            throw new eppException('Error writing content');
        }
        return null;
    }
    
    public function createResponse($request)
    {      
        foreach ($this->responses as $req=>$res)
        {
            if ($request instanceof $req)
            {
                $response = new $res();
            }            
        }
        return $response;
    }
    
    public function addCommandResponse($command,$response)
    {
        $this->responses[$command] = $response;
    }
    
    public function getTimeout() 
    {
        return $this->timeout;
    }

    public function setTimeout($timeout) 
    {    
        $this->timeout = $timeout;
    }

    public function getUsername() 
    {
        return $this->username;
    }

    public function setUsername($username) 
    {
        $this->username = $username;
    }

    public function getPassword() 
    {        
        return $this->password;
    }

    public function setPassword($password) 
    {    
        $this->password = $password;
    }

    public function getNewPassword()
    {
        return $this->newpassword;
    }

    public function setNewPassword($password)
    {
        $this->newpassword = $password;
    }
    
    public function getHostname() 
    {
        return $this->hostname;
    }

    public function setHostname($hostname) 
    {
        $this->hostname = $hostname;
    }

    public function getPort() 
    {
        return $this->port;
    }

    public function setPort($port) 
    {    
        $this->port = $port;
    } 
    
    
    public function addDefaultNamespace($xmlns,$namespace)
    {
        $this->defaultnamespace[$namespace]='xmlns:'.$xmlns;
    }
    
    public function getDefaultNamespaces()
    {
        return $this->defaultnamespace;
    }
    
    public function setVersion($version)
    {
        $this->version = $version;
    }
    
    public function getVersion()
    {
        return $this->version;
    }
    
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function setServices($services)
    {
        $this->objuri = $services;
    }
    
    public function addService($xmlns,$namespace)
    {
        $this->objuri[$xmlns]=$namespace;
    }
    
    public function getServices()
    {
        return $this->objuri;
    }
    
    public function setExtensions($extensions)
    {
        // Remove unusable extensions from the list
        $this->exturi= $extensions;
    }
    
    public function addExtension($xmlns,$namespace)
    {
        $this->exturi[$xmlns]=$namespace;
    }
    
    public function getExtensions()
    {
        return $this->exturi;
    }
    
    public function setXpathExtensions($extensions)
    {
        $this->xpathuri = $extensions;
    }
    
    public function getXpathExtensions()
    {
        return $this->xpathuri;
    }

    private function enableLogging()
    {
        date_default_timezone_set("Europe/Amsterdam");
        $this->logging = true;
    }
    private function sendLog($email, $subject)
    {
        mail($email, $subject, implode("\n", $this->logentries));
    }
    private function showLog()
    {
        echo "==== LOG ====";
        if (property_exists($this,'logentries'))
        {
            print_r($this->logentries);
        }
    }
    private function writeLog($text)
    {
        if ($this->logging)
        {            
			//echo "-----".date("Y-m-d H:i:s")."-----".$text."-----end-----\n";
            $this->logentries[] = "-----".date("Y-m-d H:i:s")."-----".$text."-----end-----\n";
        }
    }
}
