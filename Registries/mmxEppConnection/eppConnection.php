<?php
namespace Metaregistrar\EPP;

class mmxEppConnection extends eppConnection {

    public function __construct($logging=false, $settingsfile = null) {

        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Enable DNSSEC, Donuts supports this
        parent::enableDnssec();

        parent::useExtension('fee-1.0');

    }

}
