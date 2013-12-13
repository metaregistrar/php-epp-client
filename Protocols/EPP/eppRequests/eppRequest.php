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
     * Created to be able to add new stuff to the command structure
     * @var DomElement
     */
    public $command = null;
    /**
     * Created to be able to group multiple extensions together
     * @var DomElement
     */
    public $extension = null;
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
        //$this->standalone = false;
        #$this->validateOnParse = true;
    }

    function __destruct()
    {
    }

    protected function getEpp()
    {
        if (!$this->epp)
        {
            #
            # Create base epp structure
            #
            $this->epp = $this->createElement('epp');
            $this->appendChild($this->epp);
        }
        return $this->epp;
    }

    protected function getCommand()
    {
        if (!$this->command)
        {
            #
            # Create command structure
            #
            $this->command = $this->createElement('command');
            $this->getEpp()->appendChild($this->command);
        }
        return $this->command;
    }

    protected function getExtension()
    {
        if (!$this->extension)
        {
            #
            # Create extension structure
            #
            $this->extension = $this->createElement('extension');
            $this->getCommand()->appendChild($this->extension);
        }
        return $this->extension;
    }

    public function addExtension($name,$value)
    {
        $this->getEpp()->setAttribute($name,$value);
    }


    public function addSessionId()
    {
        #
        # Remove earlier session id's to make sure session id is at the end
        #
        $remove = $this->getElementsByTagName('clTRID');
        foreach ($remove as $child)
        {
            $this->getCommand()->removeChild($child);
        }
        #
        # Add session id to the end of the command structure
        #
        $this->getCommand()->appendChild($this->createElement('clTRID',$this->sessionid));
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
				$this->getEpp()->setAttribute('xmlns:'.$xmlns,$namespace);
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