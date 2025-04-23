<?php
namespace Metaregistrar\EPP;

/*
 * This object contains all the logic to create a standard EPP command
 */


class eppResponse extends \DOMDocument {
    const RESULT_SUCCESS = '1000';
    const RESULT_SUCCESS_ACTION_PENDING = '1001';
    const RESULT_NO_MESSAGES = '1300';
    const RESULT_MESSAGE_ACK = '1301';
    const RESULT_LOGOFF_SUCCESS = '1500';
    #
    # ERROR RESPONSES
    #
    const RESULT_UNKNOWN_COMMAND = '2000';
    const RESULT_SYNTAX_ERROR = '2001';
    const RESULT_USE_ERROR = '2002';
    const RESULT_PARAMETER_MISSING = '2003';
    const RESULT_PARAMETER_RANGE_ERROR = '2004';
    const RESULT_PARAMETER_ERROR = '2005';
    const RESULT_INVALID_PROTOCOL_VERSION = '2100';
    const RESULT_INVALID_COMMAND = '2101';
    const RESULT_INVALID_OPTION = '2102';
    const RESULT_INVALID_EXTENSION = '2103';
    const RESULT_BILLING_FAILURE = '2104';
    const RESULT_NO_RENEW_POSSIBLE = '2105';
    const RESULT_NO_TRANSFER_POSSIBLE = '2106';
    const RESULT_AUTHENTICATION_ERROR = '2200';
    const RESULT_AUTHORIZATION_ERROR = '2201';
    const RESULT_INVALID_AUTHINFO = '2202';
    const RESULT_TRANSFER_PENDING = '2300';
    const RESULT_TRANSFER_NOT_PENDING = '2301';
    const RESULT_ALREADY_EXISTS = '2302';
    const RESULT_NOT_EXISTS = '2303';
    const RESULT_OBJECT_STATUS_WRONG = '2304';
    const RESULT_ASSOCIATION_EXISTS = '2305';
    const RESULT_POLICY_ERROR = '2306';
    const RESULT_UNIMPLEMENTED_SERVICE = '2307';
    const RESULT_POLICY_VIOLATION = '2308';
    const RESULT_COMMAND_FAILED = '2400';
    const RESULT_COMMAND_FAILED_CONNECTION_CLOSE = '2500';
    const RESULT_AUTHENTICATION_ERROR_CONNECTION_CLOSE = '2501';
    const RESULT_SESSION_LIMIT_EXCEEDED_CONNECTION_CLOSE = '2502';


    /**
     * A list of all the checks to identify the proper response object
     * The objects that have no specific objects to identify them have been left out
     *
     * @var array
     */
    private $exceptions = null;
    /**
     *
     * @var string Category of problem
     */
    private $problemtype;
    /**
     *
     * @var array of object uri
     */
    public $objuri;
    /**
     *
     * @var array of extended uri
     */
    public $exturi;
    /**
     * @var array of xpath uri
     */
    public $xpathuri;
    /*
     * @var array of supported languages
     */
    public $language;
    /*
     * @var array of supported versions
     */
    public $versions;

    public $originalrequest;
    /**
     *
     * @var string $defaultnamespace
     */
    public $defaultnamespace;

    public function __construct($originalrequest = null) {
        parent::__construct();
        $this->formatOutput = true;
        $this->originalrequest = $originalrequest;
        #$this->validateOnParse = true;
    }

    public function __destruct() {
    }

    public function findNamespace($namespace) {
        if (!is_null($this->xpathuri)) {
            if (is_array($this->xpathuri)) {
                if (in_array($namespace, $this->xpathuri)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function saveXML(?\DOMNode $node = NULL, $options = NULL): string {
        return str_replace("\t", '  ', parent::saveXML($node, LIBXML_NOEMPTYTAG));
    }

    public function formatContents() {
        $result = '';
        $spacing = 2;
        $text = $this->saveXML();
        $text = str_replace("\n",'',$text);
        $text = str_replace('><',">\n<",$text);
        $text = str_replace(' <'," \n<",$text);
        $output = explode("\n",$text);
        $spaces = 0;
        foreach ($output as $line) {
            if (strpos($line,'</')===0) {
                $spaces -= $spacing;
            }
            $result .= substr('                          ',0,$spaces).$line."\n";
            $spaces += $spacing;
            if (strpos($line,'?>')!==false) {
                $spaces -= $spacing;
            }
            if (strpos($line,'</')!==false) {
                $spaces -= $spacing;
            }
        }
        return $result;
    }

    public function dumpContents() {
        echo $this->formatContents();
    }

    /**
     * @return bool
     * @throws eppException
     */
    public function Success() {
        $resultcode = $this->getResultCode();
        $success = ($resultcode[0] == '1');
        if (!$success) {
            switch ($resultcode[1]) {
                case '0':
                    $this->setProblemtype('syntax');
                    break;
                case '1':
                    $this->setProblemtype('implementation-specific');
                    break;
                case '2':
                    $this->setProblemtype('security');
                    break;
                case '3':
                    $this->setProblemtype('data management');
                    break;
                case '4':
                    $this->setProblemtype('server system');
                    break;
                case '5':
                    $this->setProblemtype('connection management');
                    break;
            }
            $resultmessage = $this->getResultMessage();

            $errorstring = "Error $resultcode: $resultmessage";
            $id = null;
            $value = $this->getResultValue();
            if ($value) {
                $id = 'value:' . $value;
            }
            $resultcontactid = $this->getResultContactId();
            if ($resultcontactid) {
                $id = 'contactid:' . $resultcontactid;
            }
            $resulthostname = $this->getResultHostName();
            if ($resulthostname) {
                $id = 'hostname:' . $resulthostname;
            }
            $resultdomainname = $this->getResultDomainName();
            if ($resultdomainname) {
                $id = 'domainname:' . $resultdomainname;
            }
            $resultstatus = $this->getResultHostStatus();
            if ($resultstatus) {
                $id = 'status:' . $resultstatus;
            }
            $resultaddr = $this->getResultHostAddr();
            if ($resultaddr) {
                $id = 'hostaddr:' . $resultaddr;
            }
            if ($id) {
                $errorstring .= '; ' . $id;
            }
            $resultreason = $this->getResultReason();
            if (is_string($resultreason) && strlen($resultreason)) {
                $errorstring .= ' (' . $resultreason . ')';
            }
            if ((is_array($this->exceptions)) && (count($this->exceptions)>0)) {
                foreach ($this->exceptions as $exceptionhandler) {
                    throw new $exceptionhandler($errorstring, $resultcode, null, $resultreason, $this->saveXML(), $this);
                }
            } else {
                throw new eppException($errorstring, $resultcode, null, $resultreason, $this->saveXML(), $this);
            }

        } else {
            return true;
        }
        return false;
    }

    /**
     *
     * @param string $problemtype
     */
    public function setProblemtype($problemtype) {
        $this->problemtype = $problemtype;
    }

    /**
     *
     * @return string
     */
    public function getProblemtype() {
        return $this->problemtype;
    }

    /**
     *
     * @return null|string
     */
    public function getResultCode() {
        $result = $this->queryPath('/epp:epp/epp:response/epp:result/@code');
        if ($result) {
            return $result;
        } else {
            return '1000';
        }
    }

    /**
     * @return null|string
     */
    public function getResultMessage() {
        return $this->queryPath('/epp:epp/epp:response/epp:result/epp:msg');
    }

    /**
     * @return null|string
     */
    public function getResultReason() {
        return $this->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:reason');
    }

    /**
     * @return null|string
     */
    public function getResultValue() {
        $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:value');
        if (!$result) {
            $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:value');
        }
        return $result;
    }

    /**
     * @return null|string
     */
    public function getResultContactId() {
        $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:value/contact:id');
        if (!$result) {
            $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:value/contact:id');
        }
        return $result;
    }

    /**
     * @return null|string
     */
    public function getResultDomainName() {
        $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:value/domain:name');
        if (!$result) {
            $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:value/domain:name');
        }
        return $result;
    }

    /**
     * @return null|string
     */
    public function getResultHostName() {
        $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:value/host:name');
        if (!$result) {
            $result = $this->queryPath('/epp:epp/epp:response/epp:result/epp:value/host:name');
        }
        return $result;
    }

    /**
     * @return null|string
     */
    public function getResultHostAddr() {
        return $this->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:value/host:addr');
    }

    /**
     * @return null|string
     */
    public function getResultHostStatus() {
        return $this->queryPath('/epp:epp/epp:response/epp:result/epp:extValue/epp:value/host:status/@s');
    }

    /**
     * @return null|string
     */
    public function getServerTransactionId() {
        return $this->queryPath('/epp:epp/epp:response/epp:trID/epp:svTRID');
    }

    /**
     * @return null|string
     */
    public function getClientTransactionId() {
        return $this->queryPath('/epp:epp/epp:response/epp:trID/epp:clTRID');
    }

    public function setXpath($xpathuri) {
        if (!$this->xpathuri) {
            $this->xpathuri = $xpathuri;
        } else {
            if (is_array($xpathuri)) {
                $this->xpathuri = array_merge($this->xpathuri, $xpathuri);
            }

        }
    }


    /**
     * Checks and sees if an element is present using xpath
     * @param array $matches
     * @return boolean
     */
    public function hasElement($matches)
    {
        libxml_use_internal_errors(true);
        $xpath = $this->xPath();
        foreach($matches as $match)
        {
            $results = $xpath->query($match);

            if($results->length>0)
            {
                libxml_clear_errors();
                return true;
            }
        }
        libxml_clear_errors();
        return false;
    }


    /**
     * @return \DOMXpath
     */
    public function xPath() {
        $xpath = new \DOMXpath($this);
        $this->defaultnamespace = $this->documentElement->lookupNamespaceUri(null);
        $xpath->registerNamespace('epp', $this->defaultnamespace);
        if (is_array($this->xpathuri)) {
            foreach ($this->xpathuri as $uri => $namespace) {
                if ($namespace != 'epp') { // epp was already registered as default namespace, see above
                    $xpath->registerNamespace($namespace, $uri);
                }
            }
        }
#        if (is_array($this->exturi))
#        {
#            foreach($this->exturi as $uri=>$namespace)
#            {
#                echo "RegisterNamespace exturi $namespace $uri\n";
#                $xpath->registerNamespace($namespace,$uri);
#            }
#        }
        return $xpath;
    }

    /**
     * Make an xpath query and return the results if applicable
     * @param string $path
     * @param null|\DOMElement $object
     * @return null|string
     */
    public function queryPath($path, $object = null) {
        if ($object) {
            $result = $object->getElementsByTagName($path);
        } else {
            $xpath = $this->xPath();
            $result = $xpath->query($path);
        }
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    /**
     * @param $exceptionhandler
     */
    public function addException($exceptionhandler) {
        $this->exceptions[] = $exceptionhandler;
    }

}
