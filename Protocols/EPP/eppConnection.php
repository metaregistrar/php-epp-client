<?php
namespace Metaregistrar\EPP;

class eppConnection {

    /**
     * Prevents loading the settings more then once
     * @var bool
     */
    protected $settingsloaded = false;

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

    /*
    * Number of times read operations will be retried
    * @var integer
    */
    protected $retry = 5;

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
    protected $defaultnamespace = array('xmlns' => 'urn:ietf:params:xml:ns:epp-1.0');

    /**
     * Base objects
     * @var array of accepted object URI's
     */
    protected $objuri = array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact', 'urn:ietf:params:xml:ns:host-1.0' => 'host');

    /**
     * Object extensions
     * @var array of accepted URI's for each object
     */
    protected $exturi;

    /**
     * Exception extensions
     * @var array of exception handlers
     */
    protected $exceptions = null;

    /**
     * Base objects
     * @var array of accepted URI's for xpath
     */
    protected $xpathuri = array('urn:ietf:params:xml:ns:epp-1.0' => 'epp', 'urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact', 'urn:ietf:params:xml:ns:host-1.0' => 'host');

    /**
     * These namespaces are needed in the root of the EPP object
     * @var array of accepted URI's for xpath
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

    protected $launchphase = null;

    /**
     * @var resource
     */
    protected $sslContext = null;

    /**
     * Path to certificate file
     * @var string
     */
    protected $local_cert_path = null;

    /**
     * Path to private key file
     * @var string
     */
    protected $local_pk_path = null;

    /**
     * Password of certificate file
     * @var string
     */
    protected $local_cert_pwd = null;

    /**
     * Allow/Deny self signed certificates
     * @var boolean
     */
    protected $allow_self_signed = null;

    /**
     * Require verification of SSL certificate used
     * @var boolean
     */
    protected $verify_peer = true;

    /**
     * Require verification of peer name
     * @var boolean
     */
    protected $verify_peer_name = true;

    /**
     * @var bool Using stream_set_blocking true or false on connections
     */
    protected $blocking = false;

    protected $logentries = array();

    protected $checktransactionids = true;

    /**
     * @var bool Is the client connected to the server
     */
    protected $connected = false;

    /**
     * @var bool Is the client logged in to the server
     */
    protected $loggedin = false;

    /**
     * @var null|string
     */
    protected $connectionComment = null;

    /**
     * @var null|string
     */
    protected $sourceIpAddr = null;

    /**
     * @var null|string
     */
    protected $logFile = null;

    /**
     * @param string $settingsfile
     * @param bool|false $debug
     * @return mixed
     * @throws eppException
     */
    static function create($settingsfile, $debug=false) {
        $result = self::loadSettings(null,$settingsfile);
        if ($result) {
            if (isset($result['interface'])) {
                $classname = 'Metaregistrar\\EPP\\'.$result['interface'];
                $c = new $classname($debug);
                /* @var $c eppConnection */
                $c->setConnectionDetails($result);
                return $c;
            }
            return null;
        } else {
            throw new eppException("Settingsfile could not be loaded on create function");
        }

    }

    function __construct($logging = false, $settingsfile = null) {
        if ($logging) {
            $this->enableLogging();
        }
        if ($settingsfile) {
            if (!$this->settingsloaded) {
                $this->setConnectionDetails($this->loadSettings(null,$settingsfile));
            }

        }
        #
        # Initialize default values for config parameters
        #
        $this->language = 'en';
        $this->version = '1.0';
        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        $this->setTimeout(10);
        $this->setLanguage($this->language);
        $this->setVersion($this->version);
        $this->responses['Metaregistrar\\EPP\\eppHelloRequest'] = 'Metaregistrar\\EPP\\eppHelloResponse';
        $this->responses['Metaregistrar\\EPP\\eppLoginRequest'] = 'Metaregistrar\\EPP\\eppLoginResponse';
        $this->responses['Metaregistrar\\EPP\\eppLogoutRequest'] = 'Metaregistrar\\EPP\\eppLogoutResponse';
        $this->responses['Metaregistrar\\EPP\\eppPollRequest'] = 'Metaregistrar\\EPP\\eppPollResponse';
        $this->responses['Metaregistrar\\EPP\\eppCheckDomainRequest'] = 'Metaregistrar\\EPP\\eppCheckDomainResponse';
        $this->responses['Metaregistrar\\EPP\\eppCheckContactRequest'] = 'Metaregistrar\\EPP\\eppCheckContactResponse';
        $this->responses['Metaregistrar\\EPP\\eppCheckHostRequest'] = 'Metaregistrar\\EPP\\eppCheckHostResponse';
        $this->responses['Metaregistrar\\EPP\\eppInfoHostRequest'] = 'Metaregistrar\\EPP\\eppInfoHostResponse';
        $this->responses['Metaregistrar\\EPP\\eppInfoContactRequest'] = 'Metaregistrar\\EPP\\eppInfoContactResponse';
        $this->responses['Metaregistrar\\EPP\\eppInfoDomainRequest'] = 'Metaregistrar\\EPP\\eppInfoDomainResponse';
        $this->responses['Metaregistrar\\EPP\\eppCreateDomainRequest'] = 'Metaregistrar\\EPP\\eppCreateDomainResponse';
        $this->responses['Metaregistrar\\EPP\\eppCreateContactRequest'] = 'Metaregistrar\\EPP\\eppCreateContactResponse';
        $this->responses['Metaregistrar\\EPP\\eppCreateHostRequest'] = 'Metaregistrar\\EPP\\eppCreateHostResponse';
        $this->responses['Metaregistrar\\EPP\\eppDeleteDomainRequest'] = 'Metaregistrar\\EPP\\eppDeleteResponse';
        $this->responses['Metaregistrar\\EPP\\eppDeleteContactRequest'] = 'Metaregistrar\\EPP\\eppDeleteResponse';
        $this->responses['Metaregistrar\\EPP\\eppDeleteHostRequest'] = 'Metaregistrar\\EPP\\eppDeleteResponse';
        $this->responses['Metaregistrar\\EPP\\eppUndeleteRequest'] = 'Metaregistrar\\EPP\\eppUndeleteResponse';
        $this->responses['Metaregistrar\\EPP\\eppUpdateDomainRequest'] = 'Metaregistrar\\EPP\\eppUpdateResponse';
        $this->responses['Metaregistrar\\EPP\\eppUpdateContactRequest'] = 'Metaregistrar\\EPP\\eppUpdateResponse';
        $this->responses['Metaregistrar\\EPP\\eppUpdateHostRequest'] = 'Metaregistrar\\EPP\\eppUpdateResponse';
        $this->responses['Metaregistrar\\EPP\\eppRenewRequest'] = 'Metaregistrar\\EPP\\eppRenewResponse';
        $this->responses['Metaregistrar\\EPP\\eppTransferRequest'] = 'Metaregistrar\\EPP\\eppTransferResponse';
        $this->responses['Metaregistrar\\EPP\\eppCheckRequest'] = 'Metaregistrar\\EPP\\eppCheckResponse';
        $this->responses['Metaregistrar\\EPP\\eppCreateRequest'] = 'Metaregistrar\\EPP\\eppCreateResponse';
        $this->responses['Metaregistrar\\EPP\\eppUpdateRequest'] = 'Metaregistrar\\EPP\\eppUpdateResponse';
        $this->responses['Metaregistrar\\EPP\\eppDeleteRequest'] = 'Metaregistrar\\EPP\\eppDeleteResponse';
    }

    function __destruct() {
        //echo "\nMemory usage: ".memory_get_usage()." bytes \n";
        //echo "Peak memory usage: ".memory_get_peak_usage()." bytes \n\n";
        if ($this->connected) {
            if ($this->loggedin) {
                $this->logout();
            }
            $this->disconnect();
        }
        if ($this->logging) {
            if(!$this->logFile) {
                $this->showLog();
            }
        }
    }

    public function enableLaunchphase($launchphase) {
        $this->launchphase = $launchphase;
        $this->useExtension('launch-1.0');
    }

    public function getLaunchphase() {
        return $this->launchphase;
    }

    public function enableDnssec() {
        $this->useExtension('secDNS-1.1');
    }

    public function enableRgp() {
        $this->useExtension('rgp-1.0');

    }

    public function disableRgp() {
        $this->removeExtension('urn:ietf:params:xml:ns:rgp-1.0');
    }

    public function disableDnssec() {
        $this->removeExtension('urn:ietf:params:xml:ns:secDNS-1.1');
        unset($this->responses['Metaregistrar\\EPP\\eppDnssecUpdateDomainRequest']);
    }

    /**
     * @param string $certificatepath
     * @param string | null $certificatepassword
     * @param bool $selfsigned
     * @param string | null $certificatekeypath
     *
     */
    public function enableCertification($certificatepath, $certificatepassword, $selfsigned = false, $certificatekeypath = null) {
        $this->local_cert_path = $certificatepath;
        $this->local_cert_pwd = $certificatepassword;
        $this->allow_self_signed = $selfsigned;
        $this->local_pk_path = $certificatekeypath;
    }

    public function disableCertification() {
        $this->local_cert_path = null;
        $this->local_cert_pwd = null;
        $this->allow_self_signed = null;
        $this->local_pk_path = null;
    }


    /**
     * Disconnects if connected
     * @return boolean
     */
    public function disconnect() {
        if (is_resource($this->connection)) {
            //echo "fclosing $this->hostname\n";
            //@ob_flush();
            fclose($this->connection);
            $this->writeLog("Disconnected","DISCONNECT");
        }
        $this->connected = false;
        $this->loggedin = false;
        return true;
    }

    /**
     * Connect to the address and port
     * @param null $hostname
     * @param int $port
     * @return bool
     * @throws eppException
     */
    public function connect($hostname = null, $port = null) {
        if ($hostname) {
            $this->hostname = $hostname;
        }
        if ($port) {
            $this->port = $port;
        }
        if (!$this->sslContext) {
            $context = stream_context_create();
            stream_context_set_option($context, 'ssl', 'verify_peer', $this->verify_peer);
            stream_context_set_option($context, 'ssl', 'verify_peer_name', $this->verify_peer_name);
            if ($this->local_cert_path) {
                stream_context_set_option($context, 'ssl', 'local_cert', $this->local_cert_path);
                if (isset($this->local_pk_path) && (strlen($this->local_pk_path)>0)) {
                    stream_context_set_option($context, 'ssl', 'local_pk', $this->local_pk_path);
                }
                if (isset($this->local_cert_pwd) && (strlen($this->local_cert_pwd)>0)) {
                    stream_context_set_option($context, 'ssl', 'passphrase', $this->local_cert_pwd);
                }
                if (isset($this->allow_self_signed)) {
                    stream_context_set_option($context, 'ssl', 'allow_self_signed', $this->allow_self_signed);
                    stream_context_set_option($context, 'ssl', 'verify_peer', false);
                } else {
                    stream_context_set_option($context, 'ssl', 'verify_peer', $this->verify_peer);
                }
            }
            if ($this->sourceIpAddr && filter_var($this->sourceIpAddr, FILTER_VALIDATE_IP)) {
                stream_context_set_option($context, 'socket', 'bindto', $this->sourceIpAddr . ":0");
            } else if (defined("METAREGISTRAR_EPP_SOURCE_IPADDR") && filter_var(METAREGISTRAR_EPP_SOURCE_IPADDR, FILTER_VALIDATE_IP)) {
                stream_context_set_option($context, 'socket', 'bindto', METAREGISTRAR_EPP_SOURCE_IPADDR . ":0");
            }
            $this->sslContext = $context;
        }
        $this->connection = stream_socket_client($this->hostname.':'.$this->port, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $this->sslContext);
        if (is_resource($this->connection)) {
            stream_set_blocking($this->connection, $this->blocking);
            stream_set_timeout($this->connection, $this->timeout);
            if ($errno == 0) {
                $meta = stream_get_meta_data($this->connection);
                if (isset($meta['crypto'])) {
                    $this->writeLog("Stream opened to ".$this->getHostname()." port ".$this->getPort()." with protocol ".$meta['crypto']['protocol'].", cipher ".$meta['crypto']['cipher_name'].", ".$meta['crypto']['cipher_bits']." bits ".$meta['crypto']['cipher_version'],"Connection made");
                } else {
                    $this->writeLog("Stream opened to ".$this->getHostname()." port ".$this->getPort(),"Connection made");
                }
                $this->connected = true;
                $this->read();
            }
            return $this->connected;
        }

        $this->writeLog("Connection could not be opened: $errno $errstr","ERROR");
        return false;

    }

    /**
     * Performs an EPP login request and checks the result
     * @param bool $usecdata Enclose the password field with [[CDATA]]
     * @return bool
     */
    public function login($usecdata = false) {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }
        $login = new eppLoginRequest(null,$usecdata);
        if ($response = $this->request($login)) {
            $this->writeLog("Logged in","LOGIN");
            $this->loggedin = true;
            return true;
        }
        return false;
    }

    /**
     * Performs an EPP logout and checks the result
     * @return bool
     * @throws eppException
     */
    public function logout() {
        if ($this->loggedin) {
            $logout = new eppLogoutRequest();
            if ($response = $this->request($logout)) {
                $this->writeLog("Logged out","LOGOUT");
                $this->loggedin = false;
                return true;
            } else {
                throw new eppException("Logout failed: ".$response->getResultMessage(),0,null,null,$logout->saveXML());
            }
        } else {
            return true;
        }
    }

    /**
     * @param eppRequest $eppRequest
     * @return eppResponse|null
     * @throws eppException
     */
    public function request($eppRequest) {
        $check = null;
        foreach ($this->getResponses() as $req => $check) {
            if (get_class($eppRequest) == $req) {
                break;
            }
        }
        if (($response = $this->writeandread($eppRequest)) instanceof $check) {
            // $response->Success() will trigger an eppException when fails have occurred
            if ((is_array($this->exceptions)) && (count($this->exceptions)>0)) {
                foreach($this->exceptions as $exceptionhandler) {
                    $response->addException($exceptionhandler);
                }
            }
            $response->Success();
            return $response;
        } else {
            /* @var $response eppResponse */
            throw new eppException("Return class $check expected, but received a ".get_class($response)." class",0,null,null,$eppRequest->saveXML());
        }
    }

    /**
     * Enable the readsleep functionality
     * @var boolean
     */
    private $enableReadSleep = true;

    /**
     * The initial wait time in microseconds between read attempts
     * @var integer
     */
    private $readSleepTimeInitialValue = 100;

    /**
     * The maximum time between read attempts in microseconds
     * @var integer
     */
    private $readSleepTimeLimit = 100000;

    /**
     * When using the readsleep incrementor, increment the sleep time with incrementor value 1 until the
     * the sleep time exceeds this value is exceeded then switch to the second incrementor value
     * @var integer
     */
    private $readSleepTimeIncrementorLimit = 10000;

    /**
     * Enable the read sleep incrementor
     * @var boolean
     */
    private $readSleepTimeIncrementEnabled = true;

    /**
     * The initial incrementor value
     * @var integer
     */
    private $readSleepTimeIncrementor1 = 1000;

    /**
     * The second incrementor value
     * @var integer
     */
    private $readSleepTimeIncrementor2 = 100;

    /**
     * Allows the ability turn off or tweak the response read timings.
     * Disabling the readSleep will result in high CPU usage when waiting for a response from the epp server
     *
     * @param boolean $enableReadSleep
     * @param boolean $incrementorEnabled
     * @param integer  $initialReadSleepTime
     * @param integer  $limit
     * @param integer  $readSleepTimeIncrementorLimit
     * @param integer  $incrementor1
     * @param integer  $incrementor2
     */
    public function setReadTimings(
        $enableReadSleep = true,
        $incrementorEnabled = true,
        $initialReadSleepTime = 100,
        $limit = 100000,
        $readSleepTimeIncrementorLimit = 10000,
        $incrementor1 = 1000,
        $incrementor2 = 100
    ) {
        $this->enableReadSleep = $enableReadSleep;
        $this->readSleepTimeInitialValue = $initialReadSleepTime;
        $this->readSleepTimeLimit = $limit;
        $this->readSleepTimeIncrementorLimit = $readSleepTimeIncrementorLimit;
        $this->readSleepTimeIncrementEnabled = $incrementorEnabled;
        $this->readSleepTimeIncrementor1 = $incrementor1;
        $this->readSleepTimeIncrementor2 = $incrementor2;
    }

    /**
     * This will read 1 response from the connection if there is one
     * @param boolean $nonBlocking to prevent the blocking of the thread in case there is nothing to read and not wait for the timeout
     * @return string
     * @throws eppException
     */
    public function read($nonBlocking=false) {
        $content = '';
        $time = time() + $this->timeout;
        $read = "";
        while ((!isset ($length)) || ($length > 0)) {
            if (feof($this->connection)) {
                $this->loggedin = false;
                $this->connected = false;
                throw new eppException ('Unexpected closed connection by remote host...',0,null,null,$read);
            }
            //Check if timeout occured
            if (time() >= $time) {
                return false;
            }
            //If we dont know how much to read we read the first few bytes first, these contain the content-length
            //of whats to come
            if ((!isset($length)) || ($length == 0)) {
                $readLength = 4;
                //$readbuffer = "";
                $read = "";
                $useSleep = $this->enableReadSleep;
                $readSleepTime = $this->readSleepTimeInitialValue;
                $readSleepTimeLimit = $this->readSleepTimeLimit;
                $readSleepTimeIncrementorLimit = $this->readSleepTimeIncrementorLimit;
                $readSleepTimeIncrementEnabled = $this->readSleepTimeIncrementEnabled;
                $readSleepTimeIncrementor1 = $this->readSleepTimeIncrementor1;
                $readSleepTimeIncrementor2 = $this->readSleepTimeIncrementor2;
//                $loops = 0;
                while ($readLength > 0) {
//                    $loops++;
                    if ($readbuffer = fread($this->connection, $readLength)) {
                        $readLength = $readLength - strlen($readbuffer);
                        $read .= $readbuffer;
                        $time = time() + $this->timeout;
                    } elseif ($useSleep) {
                        usleep($readSleepTime);
                        if ($readSleepTimeIncrementEnabled) {
                            if ($readSleepTime < $readSleepTimeLimit) {
                                if ($readSleepTime > $readSleepTimeIncrementorLimit) {
                                    $readSleepTime += $readSleepTimeIncrementor2;
                                } else {
                                    $readSleepTime += $readSleepTimeIncrementor1;
                                }
                            }
                        }
                    }
                    //Check if timeout occured
                    if (time() >= $time) {
                        return false;
                    }
                }
                //$this->writeLog("Used $loops loops to read initial 4 bytes","READ");
                //$this->writeLog("Read 4 bytes for integer. (read: " . strlen($read) . "):$read","READ");
                $length = $this->readInteger($read) - 4;
                //$this->writeLog("Reading next: $length bytes","READ");
            }
            if ($length > 1000000) {
                throw new eppException("Packet size is too big: $length. Closing connection",0,null,null,$read);
            }
            //We know the length of what to read, so lets read the stuff
            if ((isset($length)) && ($length > 0)) {
                $time = time() + $this->timeout;
                if ($read = fread($this->connection, $length)) {
                    //$this->writeLog(print_R(socket_get_status($this->connection), true));
                    $length = $length - strlen($read);
                    $content .= $read;
                    $time = time() + $this->timeout;
                }
                if (strpos($content, 'Session limit exceeded') > 0) {
                    $read = fread($this->connection, 4);
                    $content .= $read;
                }
            }
            if($nonBlocking && strlen($content)<1)
            {
                //there is no content don't keep waiting
                break;
            }

            if (!strlen($read)) {
                usleep(100);
            }

        }
        #ob_flush();
        return $content;
    }

    /**
     * This parses the first 4 bytes into an integer for use to compare content-length
     *
     * @param string $content
     * @return integer
     */
    private function readInteger($content) {
        $int = unpack('N', substr($content, 0, 4));
        return $int[1];
    }

    /**
     * This adds the content-length to the content that is about to be written over the EPP Protocol
     *
     * @param string $content Your XML
     * @return string String to write
     */
    private function addInteger($content) {
        $int = pack('N', intval(strlen($content) + 4));
        return $int . $content;
    }

    /**
     * Write stuff over the EPP connection
     * @param string $content
     * @return bool
     * @throws eppException
     */
    public function write($content) {
        //$this->writeLog("Writing: " . strlen($content) . " + 4 bytes","WRITE");
        $content = $this->addInteger($content);
        if (!is_resource($this->connection)) {
            $this->connected = false;
            $this->loggedin = false;
            throw new eppException ('Writing while no connection is made is not supported.');
        }

        #ob_flush();
        if (fwrite($this->connection, $content)) {
            //fpassthru($this->connection);
            return true;
        }
        return false;
    }

    /**
     * Writes a request object to the stream
     *
     * @param eppRequest $content
     * @return boolean
     * @throws eppException
     */
    public function writeRequest(eppRequest $content)
    {
        //$requestsessionid = $content->getSessionId();
        $namespaces = $this->getDefaultNamespaces();
        if (is_array($namespaces)) {
            foreach ($namespaces as $id => $namespace) {
                $content->addExtension($id, $namespace);
            }
        }
        // add the connectionComment to the request's epp element
        if(is_string($this->connectionComment))
        {
            $content->epp->appendChild($content->createComment($this->connectionComment));
        }
        /*
         * $content->login is only set if this is an instance or a sub-instance of an eppLoginRequest
         */
        if ($content->login) {
            /* @var $content eppLoginRequest */
            // Set username for login request
            $content->addUsername($this->getUsername());
            // Set password for login request
            $content->addPassword($this->getPassword());
            // Set 'new password' for login request
            if ($this->getNewPassword()) {
                $content->addNewPassword($this->getNewPassword());
            }
            // Add version to this object
            $content->addVersion($this->getVersion());
            // Add language to this object
            $content->addLanguage($this->getLanguage());
            // Add services and extensions to this content
            $content->addServices($this->getServices(), $this->getExtensions());
        }
        /*
         * $content->hello is only set if this is an instance or a sub-instance of an eppHelloRequest
         */
        if (!($content->hello)) {
            /**
             * Add used namespaces to the correct places in the XML
             */
            $content->addNamespaces($this->getServices());
            $content->addNamespaces($this->getExtensions());
        }
        $content->formatOutput = false;
        if ($this->write($content->saveXML(null, LIBXML_NOEMPTYTAG))) {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Reads a response asynchronously.
     * Warning if you don't retrieve the read this before you disconnect your response may be lost
     * please check if your object you are awaiting is supported in the eppResponse object by looking at the eppResponse
     * object's array property named "matcher"
     *
     * @return eppResponse
     * @throws eppException
     */
    public function readResponse()
    {
        $response = new eppResponse();
        $xml = $this->read(true);
        if (strlen($xml)) {
            if ($response->loadXML($xml)) {

                //$response = $response->instantiateProperResponse();
                $this->writeLog($response->saveXML(null, LIBXML_NOEMPTYTAG), "READ");

                //$clienttransid = $response->getClientTransactionId();
                $response->setXpath($this->getServices());
                $response->setXpath($this->getExtensions());
                $response->setXpath($this->getXpathExtensions());
                if ($response instanceof eppHelloResponse) {
                    $response->validateServices($this->getLanguage(), $this->getVersion());
                }
                return $response;
            }
        }
        return null;
    }

    /**
     * Error handler for loadxml() so that a nice exception is thrown
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     * @return bool
     * @throws eppException
     */
    function HandleXmlError($errno, $errstr, $errfile, $errline)
    {
        if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0)) {
            throw new eppException('ERROR reading EPP message: '.str_replace('DOMDocument::loadXML(): ','',$errstr),$errno, null, $errfile.'('.$errline.')');
        }
        else
            return false;
    }

    /**
     * Write the content domDocument to the stream
     * Read the answer
     * Load the answer in a response domDocument
     * return the reponse
     *
     * @param eppRequest $content
     * @return eppResponse
     * @throws eppException
     */
    public function writeandread($content) {
        $requestsessionid = $content->getSessionId();
        $namespaces = $this->getDefaultNamespaces();
        if (is_array($namespaces)) {
            foreach ($namespaces as $id => $namespace) {
                $content->addExtension($id, $namespace);
            }
        }
        // add the connectionComment to the request's epp element
        if(is_string($this->connectionComment))
        {
            $content->epp->appendChild($content->createComment($this->connectionComment));
        }
        /*
         * $content->login is only set if this is an instance or a sub-instance of an eppLoginRequest
         */
        if ($content->login) {
            /* @var $content eppLoginRequest */
            // Set username for login request
            $content->addUsername($this->getUsername());
            // Set password for login request
            $content->addPassword($this->getPassword());
            // Set 'new password' for login request
            if ($this->getNewPassword()) {
                $content->addNewPassword($this->getNewPassword());
            }
            // Add version to this object
            $content->addVersion($this->getVersion());
            // Add language to this object
            $content->addLanguage($this->getLanguage());
            // Add services and extensions to this content
            $content->addServices($this->getServices(), $this->getExtensions());
        }
        /*
         * $content->hello is only set if this is an instance or a sub-instance of an eppHelloRequest
         */
        if ((!($content->hello)) && (!($content->login))) {
            /**
             * Add used namespaces to the correct places in the XML
             */
            $content->addNamespaces($this->getServices());
            $content->addNamespaces($this->getExtensions());
        }
        $response = $this->createResponse($content);
        /* @var $response eppResponse */
        if (!$response) {
            throw new eppException("No valid response from server",0,null,null,$content);
        }
        $content->preserveWhiteSpace = false;
        $content->formatOutput = true;
        $this->writeLog($content->saveXML(null, LIBXML_NOEMPTYTAG),"WRITE");

        //print_r($content->saveXML(null, LIBXML_NOEMPTYTAG)); #XML Debug Output

        $content->formatOutput = false;
        if ($this->write($content->saveXML(null, LIBXML_NOEMPTYTAG))) {
            $readcounter = 0;
            $xml = $this->read();
            // When no data is present on the stream, retry reading several times
            while ((strlen($xml)==0) && ($readcounter < $this->retry)) {
                $xml = $this->read();
                $readcounter++;
            }

            if (strlen($xml)) {
                set_error_handler(array($this,'HandleXmlError'));
                if ($response->loadXML($xml)) {
                    restore_error_handler();
                    $response->preserveWhiteSpace = false;
                    $response->formatOutput = true;
                    $this->writeLog($response->formatContents(), "READ");
                    $clienttransid = $response->getClientTransactionId();
                    if (($this->checktransactionids) && ($clienttransid) && ($clienttransid != $requestsessionid) && ($clienttransid!='{{clTRID}}')) {
                        throw new eppException("Client transaction id $requestsessionid does not match returned $clienttransid",0,null,null,$xml);
                    }
                    $response->setXpath($this->getServices());
                    $response->setXpath($this->getExtensions());
                    $response->setXpath($this->getXpathExtensions());
                    if ($response instanceof eppHelloResponse) {
                        /* @var $response eppHelloResponse */
                        $response->validateServices($this->getLanguage(), $this->getVersion());
                    }
                    return $response;
                } else {
                    restore_error_handler();
                }
            } else {
                throw new eppException('Empty XML document when receiving data!');
            }
        } else {
            throw new eppException('Error writing content',0,null,null,$content);
        }
        return null;
    }

    public function createResponse($request) {
        $response = null;
        foreach ($this->getResponses() as $req => $res) {
            if (get_class($request) == $req) {
                $response = new $res($request);
                break;
            }
        }
        if (!$response) {
            throw new eppException('No valid response class found for request class '.get_class($request));
        }
        return $response;
    }

    public function addCommandResponse($command, $response) {
        $this->responses[$command] = $response;
    }

    public function getCheckTransactionIds() {
        return $this->checktransactionids;
    }

    public function setCheckTransactionIds($value) {
        $this->checktransactionids = $value;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getNewPassword() {
        return $this->newpassword;
    }

    public function setNewPassword($password) {
        $this->newpassword = $password;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    public function setLogFile($filename) {
        $this->logFile = $filename;
    }

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function getSslContext() {
        return $this->sslContext;
    }

    public function setSslContext($sslContext) {
        $this->sslContext = $sslContext;
    }

    public function setVerifyPeer($verify_peer) {
        $this->verify_peer = $verify_peer;
    }

    public function setVerifyPeerName($verify_peer_name) {
        $this->verify_peer_name = $verify_peer_name;
    }

    /**
     * @param boolean $allow_self_signed
     */
    public function setAllowSelfSigned(bool $allow_self_signed) {
        $this->allow_self_signed = $allow_self_signed;
    }

    public function getRetry()
    {
        return $this->retry;
    }

    public function setRetry($retry)
    {
        $this->retry = $retry;
    }

    public function addDefaultNamespace($xmlns, $namespace, $addxmlns=true) {
        if ($addxmlns) {
            $this->defaultnamespace[$namespace] = 'xmlns:' . $xmlns;
        } else {
            $this->defaultnamespace[$namespace] = $xmlns;
        }
    }

    public function getDefaultNamespaces() {
        return $this->defaultnamespace;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function setBlocking($blocking) {
        $this->blocking = $blocking;
    }

    public function getBlocking() {
        return $this->blocking;
    }

    public function getResponses() {
        return $this->responses;
    }

    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set service list with one call
     * @param array $services
     */
    public function setServices($services) {
        $this->objuri = $services;
    }

    /**
     * Get all supported services
     * @return array
     */
    public function getServices() {
        return $this->objuri;
    }

    /**
     * Set all extensions in one call
     * @param array $extensions
     */
    public function setExtensions($extensions) {
        // Set all extensions at once in an array
        $this->exturi = $extensions;
    }


    /**
     * Indicate a connection is going to use a specific extension and load the includes
     * @param string $namespace
     * @throws eppException
     */
    public function useExtension($namespace) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $includepath = dirname(__FILE__).'\\eppExtensions\\'.$namespace.'\\includes.php';
        } else {
            $includepath = dirname(__FILE__).'/eppExtensions/'.$namespace.'/includes.php';
        }
        if (is_file($includepath)) {
            include($includepath);
        } else {
            throw new eppException("Unable to use extension $namespace because extension files cannot be located");
        }
    }

    /**
     * Add a Service (OBJuri)
     * @param $xmlns
     * @param $namespace
     */
    public function addService($xmlns, $namespace) {
        $this->objuri[$namespace] = $xmlns;
    }

    /**
     * Add an extension (EXTuri)
     * @param string $xmlns
     * @param string $namespace
     */
    public function addExtension($xmlns, $namespace) {
        $this->exturi[$namespace] = $xmlns;
    }

    public function addException($exceptionhandler) {
        $this->exceptions[] = $exceptionhandler;
    }

    public function removeExtension($namespace) {
        unset($this->exturi[$namespace]);
    }

    public function getExtensions() {
        return $this->exturi;
    }

    public function setXpathExtensions($extensions) {
        $this->xpathuri = $extensions;
    }

    public function getXpathExtensions() {
        return $this->xpathuri;
    }

    /**
     * Enables logging
     */
    private function enableLogging() {
        $this->logging = true;
    }

    /**
     * Store the settings details ($result) in local variables for later use
     * @param array $result
     * @return bool
     */
    public function setConnectionDetails($result) {
        $this->setHostname($result['hostname']);
        $this->setUsername($result['userid']);
        $this->setPassword($result['password']);

        if (array_key_exists('port',$result)) {
            $this->setPort($result['port']);
        } else {
            $this->setPort(700);
        }

        if (array_key_exists('timeout',$result)) {
            $this->setTimeout($result['timeout']);
        } else {
            $this->setTimeout(10);
        }

        if (array_key_exists('logging',$result)) {
            if (($result['logging']=='true') || ($result['logging']=='yes') || ($result['logging']=='1')) {
                $this->enableLogging();
            }
        }
        if (array_key_exists('verifypeer',$result)) {
            if (($result['verifypeer']=='true') || ($result['verifypeer']=='yes') || ($result['verifypeer']=='1')) {
                $this->verify_peer = true;
            } else {
                $this->verify_peer = false;
            }
        }
        if (array_key_exists('verifypeername',$result)) {
            if (($result['verifypeername']=='true') || ($result['verifypeername']=='yes') || ($result['verifypeername']=='1')) {
                $this->verify_peer_name = true;
            } else {
                $this->verify_peer_name = false;
            }
        }
        if (array_key_exists('allowselfsigned',$result)) {
            if (($result['allowselfsigned']=='true') || ($result['allowselfsigned']=='yes') || ($result['allowselfsigned']=='1')) {
                $this->allow_self_signed = true;
            } else {
                $this->allow_self_signed = false;
            }
        }
        if (array_key_exists('certificatefile',$result)) {
            $this->enableCertification(
                $result['certificatefile'],
                array_key_exists('certificatepassword',$result) ? $result['certificatepassword'] : null,
                $this->allow_self_signed,
                array_key_exists('certificatekey',$result) ? $result['certificatekey'] : null
            );
        }

        $this->settingsloaded = true;
        return true;
    }

    /**
     * @param null|string $directory
     * @param string $settingsfile
     * @return array
     * @throws eppException
     */
    static function loadSettings($directory, $settingsfile) {
        if ($directory) {
            $path = $directory . '/' . $settingsfile;
        } else {
            $path = $settingsfile;
        }
        if (is_readable($path)) {
            $result = [];
            $settings = file($path, FILE_IGNORE_NEW_LINES);
            foreach ($settings as $setting) {
                if (strlen(trim($setting))>0) {
                    list($param, $value) = explode('=', $setting, 2);
                    $param = trim($param);
                    $value = trim($value);
                    $result[$param] = $value;
                }
            }
            return $result;
        } else {
            throw new eppException("$settingsfile Settings file not readable on loadSettings function");
        }
    }

    /**
     * Returns if the session is still open
     * @return bool
     */
    public function isConnected() {
        return $this->connected;
    }

    /**
     * Return if the system is still logged in
     * @return bool
     */
    public function isLoggedin() {
        return $this->loggedin;
    }

    private function showLog() {
        echo "==== LOG ====\n";
        if (property_exists($this, 'logentries')) {
            foreach ($this->logentries as $logentry) {
                echo $logentry . "\n";
            }
        }
    }

    protected function writeLog($text,$action) {
        if ($this->logging) {
            // Hide userid in the logging
            $text = $this->hideTextBetween($text,'<clID>','</clID>');
            // Hide password in the logging
            $text = $this->hideTextBetween($text,'<pw>','</pw>');
            $text = $this->hideTextBetween($text,'<pw><![CDATA[',']]></pw>');
            // Hide new password in the logging
            $text = $this->hideTextBetween($text,'<newPW>','</newPW>');
            $text = $this->hideTextBetween($text,'<newPW><![CDATA[',']]></newPW>');
            // Hide domain password in the logging
            $text = $this->hideTextBetween($text,'<domain:pw>','</domain:pw>');
            $text = $this->hideTextBetween($text,'<domain:pw><![CDATA[',']]></domain:pw>');
            // Hide contact password in the logging
            $text = $this->hideTextBetween($text,'<contact:pw>','</contact:pw>');
            $text = $this->hideTextBetween($text,'<contact:pw><![CDATA[',']]></contact:pw>');
            //echo "-----".date("Y-m-d H:i:s")."-----".$text."-----end-----\n";
            $log = "-----" . $action . "-----" . date("Y-m-d H:i:s") . "-----\n" . $text . "\n-----END-----" . date("Y-m-d H:i:s") . "-----\n";
            $this->logentries[] = $log;
            if($this->logFile) {
                file_put_contents($this->logFile, "\n".$log, FILE_APPEND);
            }
        }
    }

    /**
     * @param $text
     * @param $start
     * @param $end
     * @return string
     */
    protected function hideTextBetween($text, $start, $end) {
        if (($startpos = strpos(strtolower($text),strtolower($start))) !== false) {
            if (($endpos = strpos(strtolower($text),strtolower($end))) !== false) {
                $text = substr($text,0,$startpos+strlen($start)).'XXXXXXXXXXXXXXXX'.substr($text,$endpos,99999);
            }
        }
        return $text;
    }

    /**
     * @param null|string $connectionComment
     * @return eppConnection
     */
    public function setConnectionComment($connectionComment) {
        $this->connectionComment = $connectionComment;
        return $this;
    }

    /**
     * @param null|string $sourceIpAddr
     * @return eppConnection
     */
    public function setsourceIpAddr($sourceIpAddr) {
        $this->sourceIpAddr = $sourceIpAddr;
        return $this;
    }




}
