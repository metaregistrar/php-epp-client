<?php
namespace Metaregistrar\EPP;

class ficoraEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);
        
        // Specify timeout values in seconds, test interface is kind of slow
        parent::setTimeout(200);
        // Enable DNSSEC, Ficora supports this
        parent::enableDnssec();

        // Make sure services are not added to the main EPP commands
        parent::setServices(null);

        // Not a real extension, but okay then
        parent::useExtension('ficora');
    }

}