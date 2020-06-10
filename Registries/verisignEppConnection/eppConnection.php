<?php
namespace Metaregistrar\EPP;

class verisignEppConnection extends eppConnection {
    
    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Default server configuration stuff - this varies per connected registry
        // Check the greeting of the server to see which of these values you need to add
        parent::useExtension('verisign');
        $this->enableRgp();
        $this->enableDnssec();
        parent::enableLaunchphase('claims');
    }

}
