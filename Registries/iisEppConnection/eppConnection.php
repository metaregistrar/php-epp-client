<?php
namespace Metaregistrar\EPP;

class iisEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Specify timeout values in seconds
        // Enable DNSSEC, IIS.SE supports this
        parent::enableDnssec();

        // They have registered their own extension
        parent::useExtension('iis-1.2');

    }

}