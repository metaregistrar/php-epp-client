<?php
namespace Metaregistrar\EPP;
//define("NAMESPACESINROOT", false);

class metaregEppConnection extends eppConnection {
    
    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::useExtension('polldata-1.0');
        parent::useExtension('command-ext-1.0');
        //parent::useExtension('ext-1.0');
        $this->enableDnssec();
        //$this->enableRgp();
        //parent::enableLaunchphase('claims');
    }

}
