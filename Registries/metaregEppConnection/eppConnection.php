<?php
namespace Metaregistrar\EPP;

class metaregEppConnection extends eppConnection {
    
    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::useExtension('polldata-1.0');
        parent::useExtension('command-ext-1.0');
        parent::useExtension('ext-1.0');
        parent::useExtension('dns-ext-1.0');
        parent::useExtension('ssl-1.0');
        $this->enableDnssec();
        $this->enableRgp();
        //parent::enableLaunchphase('claims');
    }

}
