<?php
/*
 * This object contains all the logic to create an EPP hello command
 */

class eppLoginRequest extends eppRequest
{
    
    protected $options = null;
    
    function __construct($newpassword = null)
    {
        parent::__construct();
        #
        # Login parameters
        #
        $this->login = $this->createElement('login');  
        $this->getCommand()->appendChild($this->login);
        #
        # This is only the basic command structure. 
        # Userid, password, version and language info will be added later by the connection object
        #        
        $this->addSessionId();
    }

    function __destruct()
    {
    }

    private function checkForOptions()
    {
        if (!$this->options)
        {
            $this->options = $this->createElement('options');
            $this->login->appendChild($this->options);
        }        
    }

    public function addNewPassword($password)
    {
        if (!strlen($password))
        {
            throw new eppException('No new password specified for password change');
        }
        $this->login->appendChild($this->createElement('newPW',$password));
    }

    public function addUsername($username)
    {
        if (!strlen($username))
        {
            throw new eppException('No userid specified for login attempt');
        }
        $this->login->appendChild($this->createElement('clID',$username));
    }
    
    public function addPassword($password)
    {
        if (!strlen($password))
        {
            throw new eppException('No password specified for login attempt');
        }
        $this->login->appendChild($this->createElement('pw',$password));        
    }
            
    public function addVersion($version)
    {
        $this->checkForOptions();
        if (!strlen($version))
        {
            throw new eppException('No version number specified for login attempt');
        }        
        $this->options->appendChild($this->createElement('version',$version));
    }
    
    public function addLanguage($language)
    {
        $this->checkForOptions();
        if (!strlen($language))
        {
            throw new eppException('No language specified for login attempt');
        }         
        $this->options->appendChild($this->createElement('lang',$language));
    }
    
    /** 
     * Add the services and extensions to the login request
     * The services and extensions are retrieved from the epp Hello response and saved in the connection object
     * The connection procedures will call this function to set the login parameters
     * 
     * @param array $services
     * @param array $extensions 
     */
    public function addServices($services,$extensions)
    {
        #
        # Login options: Requested services
        #
        if (is_array($services))
        {
            $svcs = $this->createElement('svcs');
            foreach ($services as $service=>$extra)
            {
                $svcs->appendChild($this->createElement('objURI',$service));
            }
            if ((is_array($extensions)) && (count($extensions)>0))
            {
                $svcextension=$this->createElement('svcExtension');
                foreach ($extensions as $extension=>$extra)
                {
                    $svcextension->appendChild($this->createElement('extURI',$extension));
                }
                $svcs->appendChild($svcextension);
            }
            $this->login->appendChild($svcs);            
        }   
    }    
    
}