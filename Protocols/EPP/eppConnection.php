<?php
namespace Metaregistrar\EPP;

class eppConnection {
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
     * Path to certificate file
     * @var string
     */
    protected $local_cert_path = null;

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
     * @param string $configfile
     * @param bool|false $debug
     * @return mixed
     * @throws eppException
     */
    static function create($configfile,$debug=false) {
        if ($configfile) {
            if (is_readable($configfile)) {
                $settings = file($configfile, FILE_IGNORE_NEW_LINES);
                foreach ($settings as $setting) {
                    list($param, $value) = explode('=', $setting, 2);
                    $param = trim($param);
                    $value = trim($value);
                    $result[$param] = $value;
                }

            } else {
                throw new eppException('File not found: '.$configfile);
            }
        } else {
            throw new eppException('Configuration file not specified on eppConnection:create');
        }
        if (isset($result['interface'])) {
            $classname = 'Metaregistrar\\EPP\\'.$result['interface'];
            $c = new $classname($debug);
            /* @var $c eppConnection */
            $c->setConnectionDetails($configfile);
            return $c;
        }
        return null;

    }

    function __construct($logging = false, $settingsfile = null) {
        if ($logging) {
            $this->enableLogging();
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
        $this->responses['Metaregistrar\\EPP\\eppTransferDomainRequest'] = 'Metaregistrar\\EPP\\eppTransferResponse';
        $this->responses['Metaregistrar\\EPP\\eppCheckRequest'] = 'Metaregistrar\\EPP\\eppCheckResponse';
        $this->responses['Metaregistrar\\EPP\\eppCreateRequest'] = 'Metaregistrar\\EPP\\eppCreateResponse';
        $this->responses['Metaregistrar\\EPP\\eppUpdateRequest'] = 'Metaregistrar\\EPP\\eppUpdateResponse';
        $this->responses['Metaregistrar\\EPP\\eppDeleteRequest'] = 'Metaregistrar\\EPP\\eppDeleteResponse';

        #
        # Read settings.ini or specified settings file
        #
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $path = str_replace('Metaregistrar\EPP\\',dirname(__FILE__).'\..\..\Registries\\',get_called_class());
        } else {
            $path = str_replace('Metaregistrar\EPP\\',dirname(__FILE__).'/../../Registries/',get_called_class());
        }
        if (!$settingsfile) {
            $settingsfile = 'settings.ini';
        }
        $test = pathinfo($settingsfile);
        if ($test['dirname']!='.') {
            $path = $test['dirname'];
            $settingsfile=$test['basename'];
        }
        if ($settings = $this->loadSettings($path,$settingsfile)) {
            $this->setHostname($settings['hostname']);
            $this->setUsername($settings['userid']);
            $this->setPassword($settings['password']);
            if (array_key_exists('port',$settings)) {
                $this->setPort($settings['port']);
            } else {
                $this->setPort(700);
            }
            if (array_key_exists('certificatefile',$settings) && array_key_exists('certificatepassword',$settings)) {
                if ((strpos($settings['certificatefile'],'\\')===false) && (strpos($settings['certificatefile'],'/')===false)) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        $settings['certificatefile'] = $path . '\\' . $settings['certificatefile'];
                    } else {
                        $settings['certificatefile'] = $path . '/' . $settings['certificatefile'];
                    }
                }
                if (isset($settings['allowselfsigned'])) {
                    $this->enableCertification($settings['certificatefile'], $settings['certificatepassword'], $settings['allowselfsigned']);
                } else {
                    $this->enableCertification($settings['certificatefile'], $settings['certificatepassword']);
                }
            }
        }
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
            $this->showLog();
        }
    }

    public function enableLaunchphase($launchphase) {
        $this->launchphase = $launchphase;
        $this->addExtension('launch','urn:ietf:params:xml:ns:launch-1.0');
        $this->responses['Metaregistrar\\EPP\\eppLaunchCheckRequest'] = 'Metaregistrar\\EPP\\eppLaunchCheckResponse';
        $this->responses['Metaregistrar\\EPP\\eppLaunchCreateDomainRequest'] = 'Metaregistrar\\EPP\\eppLaunchCreateDomainResponse';
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
     *
     */
    public function enableCertification($certificatepath, $certificatepassword, $selfsigned = false) {
        $this->local_cert_path = $certificatepath;
        $this->local_cert_pwd = $certificatepassword;
        $this->allow_self_signed = $selfsigned;
    }

    public function disableCertification() {
        $this->local_cert_path = null;
        $this->local_cert_pwd = null;
        $this->allow_self_signed = null;
    }


    /**
     * Disconnects if connected
     * @return boolean
     */
    public function disconnect() {
        if (is_resource($this->connection)) {
            //echo "Fclosing $this->hostname\n";
            //@ob_flush();
            fclose($this->connection);
            $this->writeLog("Disconnected","DISCONNECT");
            $this->connected = false;
        }
        return true;
    }

    /**
     * Connect to the address and port
     * @param null $hostname
     * @param int $port
     * @return bool
     * @throws eppException
     */
    public function connect($hostname = null, $port = null)
    {
        if ($hostname) {
            $this->hostname = $hostname;
        }
        if ($port) {
            $this->port = $port;
        }
        $ssl = false;
        if ($this->local_cert_path) {
            $ssl = true;
        }
        if (stripos($this->hostname,'ssl://')===false) {
            $target = sprintf('%s://%s:%d', ($ssl === true ? 'ssl' : 'tcp'), $this->hostname, $this->port);
        } else {
            $target = sprintf('%s:%d', $this->hostname, $this->port);
            $ssl = true;
        }
        $errno = '';
        $errstr = '';
        $context = stream_context_create();
        if ($ssl) {
            stream_context_set_option($context, 'ssl', 'verify_peer', false);
            stream_context_set_option($context, 'ssl', 'verify_peer_name', false);
        }
        if ($this->local_cert_path) {
            stream_context_set_option($context, 'ssl', 'local_cert', $this->local_cert_path);
        }
        if (isset($this->local_cert_pwd) && (strlen($this->local_cert_pwd) > 0)) {
            stream_context_set_option($context, 'ssl', 'passphrase', $this->local_cert_pwd);
        }
        if (isset($this->allow_self_signed)) {
            stream_context_set_option($context, 'ssl', 'allow_self_signed', $this->allow_self_signed);
        }
        if ($this->connection = stream_socket_client($target, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context)) {
            if (is_resource($this->connection)) {
                stream_set_blocking($this->connection, false);
                stream_set_timeout($this->connection, $this->timeout);
                if ($errno == 0) {
                    $this->writeLog("Connection made", "CONNECT");
                    $this->connected = true;
                    $this->read();
                    return true;
                } else {
                    $this->writeLog("Error $errno occurred during open of connection ($errstr)", "ERROR");
                    throw new eppException("Error $errno occurred during open of connection to $target: $errstr", $errno, null, $errstr);
                }
            } else {
                $this->writeLog("Connection could not be opened: $errno $errstr", "ERROR");
                throw new eppException("Connection could not be openen to $target: $errstr (code $errno)", $errno, null, $errstr);
            }
        } else {
            throw new eppException("Error connecting to $target: $errstr (code $errno)", $errno, null, $errstr);
        }
    }


    /**
     * Connect to the address and port
     * @param null $hostname
     * @param int $port
     * @return bool
     * @throws eppException
     */
    public function connectDEPRECATED($hostname = null, $port = null) {
        if ($hostname) {
            $this->hostname = $hostname;
        }
        if ($port) {
            $this->port = $port;
        }
        if ($this->local_cert_path) {
            $ssl = true;
            $target = sprintf('%s://%s:%d', ($ssl === true ? 'ssl' : 'tcp'), $this->hostname, $this->port);
            $errno = '';
            $errstr = '';
            $context = stream_context_create();
            stream_context_set_option($context, 'ssl', 'local_cert', $this->local_cert_path);
            if (isset($this->local_cert_pwd) && (strlen($this->local_cert_pwd)>0)) {
                stream_context_set_option($context, 'ssl', 'passphrase', $this->local_cert_pwd);
            }
            if (isset($this->allow_self_signed)) {
                stream_context_set_option($context, 'ssl', 'allow_self_signed', $this->allow_self_signed);
            }
            if ($this->connection = stream_socket_client($target, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context)) {
                $this->writeLog("Connection made","CONNECT");
                $this->connected = true;
                $this->read();
                return true;
            } else {
                throw new eppException("Error connecting to $target: $errstr (code $errno)",$errno,null,$errstr);
            }
        } else {
            //We don't want our error handler to kick in at this point...
            putenv('SURPRESS_ERROR_HANDLER=1');
            #echo "Connecting: $this->hostname:$this->port\n";
            #$this->writeLog("Connecting: $this->hostname:$this->port");
            $context = stream_context_create();
            stream_context_set_option($context, 'ssl','verify_peer',false);
            stream_context_set_option($context, 'ssl','verify_peer_name',false);
            $this->connection = stream_socket_client($this->hostname.':'.$this->port, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context);
            putenv('SURPRESS_ERROR_HANDLER=0');
            if (is_resource($this->connection)) {
                stream_set_blocking($this->connection, false);
                stream_set_timeout($this->connection, $this->timeout);
                if ($errno == 0) {
                    $this->writeLog("Connection made","CONNECT");
                    $this->connected = true;
                    $this->read();
                    return true;
                } else {
                    return false;
                }
            } else {
                $this->writeLog("Connection could not be opened: $errno $errstr","ERROR");
                return false;
            }
        }
    }

    /**
     * Performs an EPP login request and checks the result
     * @return bool
     */
    public function login() {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }
        $login = new eppLoginRequest;
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
            $logout = new eppLogoutRequest();
        if ($response = $this->request($logout)) {
                $this->writeLog("Logged out","LOGOUT");
                $this->loggedin = false;
                return true;
            } else {
                throw new eppException("Logout failed: ".$response->getResultMessage(),0,null,null,$logout->saveXML());
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
            $response->Success();
            return $response;
        } else {
            /* @var $response eppResponse */
            throw new eppException("Return class $check expected, but received a ".get_class($response)." class",0,null,null,$eppRequest->saveXML());
        }
    }

    /**
     * This will read 1 response from the connection if there is one
     * @param boolean $nonBlocking to prevent the blocking of the thread in case there is nothing to read and not wait for the timeout
     * @return string
     * @throws eppException
     */
    public function read($nonBlocking=false) {
        putenv('SURPRESS_ERROR_HANDLER=1');
        $content = '';
        $time = time() + $this->timeout;
        $read = "";
        while ((!isset ($length)) || ($length > 0)) {
            if (feof($this->connection)) {
                putenv('SURPRESS_ERROR_HANDLER=0');
                $this->loggedin = false;
                $this->connected = false;
                throw new eppException ('Unexpected closed connection by remote host...',0,null,null,$read);
            }
            //Check if timeout occured
            if (time() >= $time) {
                putenv('SURPRESS_ERROR_HANDLER=0');
                return false;
            }
            //If we dont know how much to read we read the first few bytes first, these contain the content-length
            //of whats to come
            if ((!isset($length)) || ($length == 0)) {
                $readLength = 4;
                //$readbuffer = "";
                $read = "";
                while ($readLength > 0) {
                    if ($readbuffer = fread($this->connection, $readLength)) {
                        $readLength = $readLength - strlen($readbuffer);
                        $read .= $readbuffer;
                        $time = time() + $this->timeout;
                    }
                    //Check if timeout occured
                    if (time() >= $time) {
                        putenv('SURPRESS_ERROR_HANDLER=0');
                        return false;
                    }
                }
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
        putenv('SURPRESS_ERROR_HANDLER=0');
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
            throw new eppException ('Writing while no connection is made is not supported.');
        }

        putenv('SURPRESS_ERROR_HANDLER=1');
        #ob_flush();
        if (fwrite($this->connection, $content)) {
            //fpassthru($this->connection);
            putenv('SURPRESS_ERROR_HANDLER=0');
            return true;
        }
        putenv('SURPRESS_ERROR_HANDLER=0');
        return false;
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
        if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0))
        {
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
        $response = $this->createResponse($content);
        /* @var $response eppResponse */
        if (!$response) {
            throw new eppException("No valid response from server",0,null,null,$content);
        }
        $content->formatOutput = true;
        $this->writeLog($content->saveXML(null, LIBXML_NOEMPTYTAG),"WRITE");
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
                    $this->writeLog($response->saveXML(null, LIBXML_NOEMPTYTAG),"READ");
                    $clienttransid = $response->getClientTransactionId();
                    if (($this->checktransactionids) && ($clienttransid) && ($clienttransid != $requestsessionid)) {
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

    /**
     * Create a proper response structure based on the request that wase done
     * If the response belonging to the request is not set, an exception is thrown
     *
     * @param $request
     * @return null
     * @throws eppException
     */
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

    /**
     * Add a new command-response pair. Used mostly by extension that want to add new commands and new responses to existing commands
     *
     * @param $command
     * @param $response
     */

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

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function getRetry()
    {
        return $this->retry;
    }

    public function setRetry($retry)
    {
        $this->retry = $retry;
    }

    public function addDefaultNamespace($xmlns, $namespace) {
        $this->defaultnamespace[$namespace] = 'xmlns:' . $xmlns;
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
     * Add a service to the list of services
     * @param string $xmlns
     * @param string $namespace
     */
    public function addService($xmlns, $namespace) {
        $this->objuri[$xmlns] = $namespace;
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
     * Add an extension to the Login command of the EPP connection
     * The login command will specify which extensions will be used in this session
     *
     * @param string $xmlns
     * @param string $namespace
     */
    public function addExtension($xmlns, $namespace) {
        $this->exturi[$namespace] = $xmlns;
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

    private function enableLogging() {
        date_default_timezone_set("Europe/Amsterdam");
        $this->logging = true;
    }

    public function setConnectionDetails($settingsfile) {
        $result = array();
        if (is_readable($settingsfile)) {
            $settings = file($settingsfile, FILE_IGNORE_NEW_LINES);
            foreach ($settings as $setting) {
                list($param, $value) = explode('=', $setting, 2);
                $param = trim($param);
                $value = trim($value);
                $result[$param] = $value;
            }
            $this->setHostname($result['hostname']);
            $this->setUsername($result['userid']);
            $this->setPassword($result['password']);
            if (array_key_exists('port',$result)) {
                $this->setPort($result['port']);
            } else {
                $this->setPort(700);
            }
            if (array_key_exists('certificatefile',$result) && array_key_exists('certificatepassword',$result)) {
                // Enter the path to your certificate and the password here
                $this->enableCertification($result['certificatefile'], $result['certificatepassword']);
            } elseif (array_key_exists('certificatefile',$result)) {
		// Enter the path to your certificate without password
                $this->enableCertification($result['certificatefile'], null);
            }
            return true;
        } else {
            throw new eppException("Settings file $settingsfile could not be opened");
        }
    }

    protected function loadSettings($directory, $settingsfile) {
        $result = array();
        if (is_readable($directory . '/'.$settingsfile)) {
            $settings = file($directory . '/' . $settingsfile, FILE_IGNORE_NEW_LINES);
            foreach ($settings as $setting) {
                list($param, $value) = explode('=', $setting, 2);
                $param = trim($param);
                $value = trim($value);
                $result[$param] = $value;
            }
            return $result;
        }
        return null;
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
            //echo "-----".date("Y-m-d H:i:s")."-----".$text."-----end-----\n";
            $this->logentries[] = "-----" . $action . "-----" . date("Y-m-d H:i:s") . "-----\n" . $text . "\n-----END-----" . date("Y-m-d H:i:s") . "-----\n";
        }
    }
}
