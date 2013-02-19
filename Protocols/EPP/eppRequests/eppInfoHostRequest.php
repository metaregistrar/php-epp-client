<?php
include_once(dirname(__FILE__).'/../eppRequest.php');
/*
 * This object contains all the logic to create an EPP host:info command
 */

class eppInfoHostRequest extends eppRequest
{
    function __construct($inforequest)
    {
        parent::__construct();

        if ($inforequest instanceof eppHost)
        {
            $this->setHost($inforequest);
        }
        else
        {
            throw new eppException('parameter of infohostrequest needs to be eppHost object');
        }
        $this->addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    
    public function setHost(eppHost $host)
    {
        #
        # Create command structure
        #
        $this->command = $this->createElement('command');
        #
        # Domain check structure
        #
        $info = $this->createElement('info');
        $this->hostobject = $this->createElement('host:info');        
        $this->hostobject->appendChild($this->createElement('host:name',$host->getHostname()));
        $info->appendChild($this->hostobject);
        $this->command->appendChild($info);
        $this->epp->appendChild($this->command);
    }    
}
