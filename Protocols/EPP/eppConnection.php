<?php
namespace Metaregistrar\EPP;

class eppConnection extends eppBase {
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
    protected $xpathuri = ['urn:ietf:params:xml:ns:epp-1.0' => 'epp', 'urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact', 'urn:ietf:params:xml:ns:host-1.0' => 'host'];

    /**
     * These namespaces are needed in the root of the EPP object
     * @var array of accepted URI's for xpath
     */
    protected $rootspace = [];

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
     * Commands and equivalent responses
     * @var array
     */
    protected $responses;

    /**
     * Are we using the launchphase extensions or not
     * @var null
     */
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
    
    /**
     * Variable to check if the sent and received transaction id's remain the same
     * @var bool
     */
    protected $checktransactionids = true;

    /**
     * @var bool Is the client connected to the server
     */
    protected $connected = false;

    /**
     * @var bool Is the client logged in to the server
     */
    protected $loggedin = false;

    
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

        // Initialise default, EPP standard responses
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

        #
        # Read settings.ini or specified settings file from the directory where the called class resides
        #
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $path = str_replace('Metaregistrar\EPP\\',dirname(__FILE__).'\..\..\Registries\\',get_called_class());
        } else {
            $path = str_replace('Metaregistrar\EPP\\',dirname(__FILE__).'/../../Registries/',get_called_class());
        }
        if (!$settingsfile) {
            $settingsfile = 'settings.ini';
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
            if (array_key_exists('certificatefile',$settings)) {
                if ((strpos($settings['certificatefile'],'\\')===false) && (strpos($settings['certificatefile'],'/')===false)) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        $settings['certificatefile'] = $path . '\\' . $settings['certificatefile'];
                    } else {
                        $settings['certificatefile'] = $path . '/' . $settings['certificatefile'];
                    }
                }
                $certpassword = null;
                if (array_key_exists('certificatepassword',$settings)) {
                    $certpassword = $settings['certificatepassword'];
                }
                $allowselfsigned = null;
                if (array_key_exists('allowselfsigned',$settings)) {
                    $allowselfsigned = $settings['allowselfsigned'];
                }
                if (isset($settings['allowselfsigned'])) {
                    $this->enableCertification($settings['certificatefile'], $certpassword, $allowselfsigned);
                } else {
                    $this->enableCertification($settings['certificatefile'], $certpassword);
                }
            }
        }
    }

    function __destruct() {
        if ($this->connected) {
            if ($this->loggedin) {
                $this->logout();
            }
            $this->disconnect();
        }
        if ($this->logging) {
            $this->showLog();
            echo "\n\nMemory usage: ".memory_get_usage()." bytes";
            echo "\nPeak memory usage: ".memory_get_peak_usage()." bytes \n\n";
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
        $this->addExtension('secDNS','urn:ietf:params:xml:ns:secDNS-1.1');
        $this->responses['Metaregistrar\\EPP\\eppDnssecUpdateDomainRequest'] = 'Metaregistrar\\EPP\\eppUpdateDomainResponse';
    }

    public function enableRgp() {
        $this->addExtension('rgp','urn:ietf:params:xml:ns:rgp-1.0');
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
            @ob_flush();
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
     * @internal param string $address
     */
    public function connect($hostname = null, $port = null) {
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
            $this->connection = fsockopen($this->hostname, $this->port, $errno, $errstr, $this->timeout);
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
            $this->connect();
        }
        $login = new eppLoginRequest;
        if ((($response = $this->writeandread($login)) instanceof eppLoginResponse) && ($response->Success())) {
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
            if ((($response = $this->writeandread($logout)) instanceof eppLogoutResponse) && ($response->Success())) {
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
            if (($eppRequest instanceof $req) && (!is_subclass_of($eppRequest, $req))) {
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
     * Write the content of the domDocument object as XML to the stream
     * Read the answer
     * Load the answer in a response domDocument matching the request
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
        if ((!($content->hello)) && ($content->rootNamespaces())) {
            /**
             * Add used namespaces to the correct places in the XML
             * Some registries require the namespaces to be in each host, contact or domain segment
             * In that case, $this->namespacesinroot can be false to allow for the namespaces to be in each segment
             */
            $content->addNamespaces($this->getServices());
            $content->addNamespaces($this->getExtensions());
        }

        // Create the proper response object from the request we have put out
        // Response/request matches can be found in the variable $this->responses
        $response = $this->createResponse($content);

        /* @var $response /domDocument */
        if (!$response) {
            throw new eppException("No valid response from server",0,null,null,$content);
        }

        // Write the response into the logfile
        $content->formatOutput = true;
        $this->writeLog($content->saveXML(null, LIBXML_NOEMPTYTAG),"WRITE");

        // Write the request to the server
        $content->formatOutput = false;
        if ($this->write($content->saveXML(null, LIBXML_NOEMPTYTAG))) {
            $readcounter = 0;
            $xml = $this->read();

            // When no data is present on the stream, keep on reading several times, maybe timeouts were too low
            while ((strlen($xml)==0) && ($readcounter < $this->retry)) {
                $xml = $this->read();
                $readcounter++;
            }

            if (strlen($xml)) {
                // Load the response form the server in the object we have created earlier
                if ($response->loadXML($xml)) {

                    // Write the response to the logfile
                    $this->writeLog($response->saveXML(null, LIBXML_NOEMPTYTAG),"READ");
                    /*
                    ob_flush();
                    */

                    // Check if the transaction ID from the response matches the one from the request
                    // You can switch this off if it gives problems
                    $clienttransid = $response->getClientTransactionId();
                    if (($this->checktransactionids) && ($clienttransid) && ($clienttransid != $requestsessionid)) {
                        throw new eppException("Client transaction id $requestsessionid does not match returned $clienttransid",0,null,null,$xml);
                    }

                    // Set the proper namespaces to process the response
                    $response->setXpath($this->getServices());
                    $response->setXpath($this->getExtensions());
                    $response->setXpath($this->getXpathExtensions());

                    // If it is an Hello response, validate if the language and version matches
                    if ($response instanceof eppHelloResponse) {
                        $response->validateServices($this->getLanguage(), $this->getVersion());
                    }
                    return $response;
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
        $response = new eppResponse();
        foreach ($this->getResponses() as $req => $res) {
            if (($request instanceof $req) && (!is_subclass_of($request,$req))) {
                $response = new $res($request);
                break;
            }
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
     * @param string $xmlns
     * @param string $namespace
     * @param bool $addtonamespace
     */
    public function addExtension($xmlns, $namespace, $addtonamespace = true) {
        if ($addtonamespace) {
            $this->exturi[$namespace] = $xmlns;
        }
        // Include the extension data, request and response files
        $pos = strrpos($namespace,'/');
        if ($pos!==false) {
            $path = substr($namespace,$pos+1,999);
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $includepath = dirname(__FILE__).'\\eppExtensions\\'.$path.'\\includes.php';
            } else {
                $includepath = dirname(__FILE__).'/eppExtensions/'.$path.'/includes.php';
            }

        } else {
            $pos = strrpos($namespace,':');
            if ($pos!==false) {
                $path = substr($namespace,$pos+1,999);
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $includepath = dirname(__FILE__).'\\eppExtensions\\'.$path.'\\includes.php';
                } else {
                    $includepath = dirname(__FILE__).'/eppExtensions/'.$path.'/includes.php';
                }

            } else {
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $includepath = dirname(__FILE__).'\\eppExtensions\\'.$namespace.'\\includes.php';
                } else {
                    $includepath = dirname(__FILE__).'/eppExtensions/'.$namespace.'/includes.php';
                }

            }
        }
        if (is_file($includepath)) {
            include_once($includepath);
        }
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
     * Creates all needed settings from the settinsgfile
     * @param string $settingsfile
     * @return bool
     * @throws eppException
     */
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
            if (array_key_exists('certificatefile',$result)) {
                // Enter the path to your certificate and the password here
                if (array_key_exists('certificatepassword',$result)) {
                    $this->enableCertification($result['certificatefile'], $result['certificatepassword']);
                } else {
                    $this->enableCertification($result['certificatefile'], null);
                }

            }
            return true;
        } else {
            throw new eppException("Settings file $settingsfile could not be opened");
        }
    }

    protected function loadSettings($directory, $settingsfile) {
        $result = array();
        if (is_readable($directory . '/'.$settingsfile)) {
            $settings = file($directory . '/'.$settingsfile, FILE_IGNORE_NEW_LINES);
            foreach ($settings as $setting) {
                list($param, $value) = explode('=', $setting,2);
                $param = trim($param);
                $value = trim($value);
                $result[$param] = $value;
            }
            return $result;
        }
        return null;
    }


}
