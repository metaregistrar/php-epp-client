<?php
namespace Metaregistrar\EPP;

class frlEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        parent::__construct($logging);
        $settings = $this->loadSettings(dirname(__FILE__));
        parent::setHostname($settings['hostname']);
        parent::setPort($settings['port']);
        parent::setUsername($settings['userid']);
        parent::setPassword($settings['password']);
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0'=>'domain','urn:ietf:params:xml:ns:contact-1.0'=>'contact'));
        parent::enableLaunchphase('claims');
        parent::enableDnssec();
    }

}