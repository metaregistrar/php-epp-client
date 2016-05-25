<?php
namespace Metaregistrar\EPP;
/*
 * This object contains all the logic to create an EPP command
 */

class eppRequest extends \DOMDocument {
    
    CONST TYPE_CHECK = 'check';
    CONST TYPE_CREATE = 'create';
    CONST TYPE_INFO = 'info';
    CONST TYPE_UPDATE = 'update';
    CONST TYPE_DELETE = 'delete';
    CONST TYPE_TRANSFER = 'transfer';
    CONST TYPE_RENEW = 'renew';
    
    /**
     * Element object to add command structures
     * @var \DomElement
     */
    public $epp = null;
    /**
     * Created to be able to add new stuff to the command structure
     * @var \DomElement
     */
    public $command = null;
    /**
     * Created to be able to group multiple extensions together
     * @var \DomElement
     */
    public $extension = null;
    /**
     *
     * @var string Unique session id
     */
    public $sessionid = null;



    /*
     * Login element
     * @var \DomElement
     */
    public $login = null;
    /*
     * Hello element
     * @var \DomElement
     */
    public $hello = null;

    /**
     * If true, namespaces are sent at the beginning of the EPP command
     * If false, namespaces are sent with each contact, domain or host object
     * @var bool
     */
    private $namespacesinroot = true;

    function __construct() {
        $this->sessionid = uniqid();
        parent::__construct('1.0', 'UTF-8');
        $this->formatOutput = true;
        //$this->standalone = false;
        #$this->validateOnParse = true;
    }

    function __destruct() {
    }

    /**
     * Determine whether the namespaces must be put in the root or at the corresponding objects 
     * @param bool $setting
     */
    public function setNamespacesinroot($setting) {
        $this->namespacesinroot = $setting;
    }
    
    public function rootNamespaces() {
        return $this->namespacesinroot;
    }
    
    protected function getEpp() {
        if (!$this->epp) {
            #
            # if its not there, then create base epp structure
            #
            $this->epp = $this->createElement('epp');
            $this->appendChild($this->epp);
        }
        return $this->epp;
    }

    protected function getCommand() {
        if (!$this->command) {
            #
            # If its not there, then create command structure
            #
            $this->command = $this->createElement('command');
            $this->getEpp()->appendChild($this->command);
        }
        return $this->command;
    }

    protected function getExtension() {
        if (!$this->extension) {
            #
            # If its not there, then create extension structure
            #
            $this->extension = $this->createElement('extension');
            $this->getCommand()->appendChild($this->extension);
        }
        return $this->extension;
    }

    public function addExtension($name, $value) {
        if ($epp = $this->getEpp()) {
            $epp->setAttribute($name, $value);
        } else {
            throw new eppException('Cannot set attribute on an empty epp element');
        }
    }
    
    public function addSessionId() {
        #
        # Remove earlier session id's to make sure session id is at the end
        #
        $remove = $this->getElementsByTagName('clTRID');
        foreach ($remove as $child) {
            $this->getCommand()->removeChild($child);
        }
        #
        # Add session id to the end of the command structure
        #
        $this->getCommand()->appendChild($this->createElement('clTRID', $this->sessionid));
    }

    public function getSessionId() {
        return $this->sessionid;
    }

    public function dumpContents() {
        echo $this->saveXML();
    }
    
    
    protected static function isAscii($str) {
        return mb_check_encoding($str, 'ASCII');
    }

    public function addNamespaces($namespaces) {
        if (is_array($namespaces)) {
            foreach ($namespaces as $namespace => $xmlns) {
                $this->getEpp()->setAttribute('xmlns:' . $xmlns, $namespace);
                /*$object = $xmlns.'object';
                if ($object == 'secDNSobject')
                {
                    // ADD SECDNS to domain string
                    $object = 'domainobject';
                }
                if (property_exists($this, $object) && ($this->$object))
                {
                    $this->$object->setAttribute('xmlns:'.$xmlns,$namespace);
                }*/
            }
        }
    }
    


    
}