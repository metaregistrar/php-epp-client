<?php
namespace Metaregistrar\EPP;

class frlEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        $settings=file(dirname(__FILE__).'/settings.ini',FILE_IGNORE_NEW_LINES);
        foreach($settings as $setting)
        {
            list($param,$value)=explode('=',$setting);
            $$param = $value;
        }
        parent::__construct($logging);
        parent::setHostname($hostname);
        parent::setPort($port);
        parent::setUsername($userid);
        parent::setPassword($password);
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0'=>'domain','urn:ietf:params:xml:ns:contact-1.0'=>'contact'));
        parent::enableLaunchphase('claims');
        parent::enableDnssec();
    }

}