<?php

/*
 * This object contains all the logic to create an EPP command
 */

class eppRequest extends DomDocument
{


    /**
     * Element object to add command structures
     * @var DomElement
     */
    public $epp = null;
    /**
     * Created to be able to add extensions to the command structure
     * @var DomElement
     */
    public $command = null;
    /**
     *
     * @var string Unique session id
     */
    public $sessionid = null;
    /**
     * DomainObject object to add namespaces to
     * @var DomElement
     */
    public $domainobject = null;
    /**
     * ContactObject object to add namespaces to
     * @var DomElement
     */
    public $contactobject = null;
    /**
     * HostObject object to add namespaces to
     * @var DomElement
     */
    public $hostobject = null;
    /*
     * Login element
     * @var DomElement
     */
    public $login = null;
    /*
     * Hello element
     * @var DomElement
     */
    public $hello = null;

    function __construct()
    {
        $this->sessionid = uniqid();
        parent::__construct('1.0', 'UTF-8');
        $this->formatOutput = true;
        $this->standalone = false;
        #$this->validateOnParse = true;
        $this->epp = $this->createElement('epp');
        $this->appendChild($this->epp);
    }

    function __destruct()
    {
    }


    public function addExtension($name,$value)
    {
        if ($this->epp)
        {
            $this->epp->setAttribute($name,$value);
        }
    }


    public function addSessionId()
    {
        #
        # Remove earlier session id's to make sure session id is at the end
        #
        $remove = $this->getElementsByTagName('clTRID');
        foreach ($remove as $child)
        {
            $this->command->removeChild($child);
        }
        #
        # Add session id to the end of the command structure
        #
        $this->command->appendChild($this->createElement('clTRID',$this->sessionid));
    }
    
    public function getSessionId()
    {
        return $this->sessionid;
    }
    
    public function addNamespaces($namespaces)
    {
        if (is_array($namespaces))
        {
            foreach ($namespaces as $namespace=>$xmlns)
            {
                $object = $xmlns.'object';
                if ($object == 'secDNSobject')
                {
                    // ADD SECDNS to domain string
                    $object = 'domainobject';
                }                
                if (property_exists($this, $object) && ($this->$object))
                {
                    $this->$object->setAttribute('xmlns:'.$xmlns,$namespace);
                }
            }
        }
    }

}