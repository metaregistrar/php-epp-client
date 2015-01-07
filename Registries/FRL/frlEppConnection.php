<?php
include_once(dirname(__FILE__).'/../../Protocols/EPP/eppConnection.php');

#
# Load the FRL specific additions
#

class frlEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        parent::__construct($logging);
        parent::setHostname('ssl://uat.nic.frl');
        parent::setPort('700');
        parent::setUsername('');
        parent::setPassword('');
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0'=>'domain','urn:ietf:params:xml:ns:contact-1.0'=>'contact'));
        parent::enableDnssec();
    }


}
