<?php
namespace Metaregistrar\EPP;


class donutsEppConnection extends eppConnection {

    public function __construct($logging=false, $settingsfile = null) {

        // Construct the EPP connection object en specify if you want logging on or off
        parent::__construct($logging, $settingsfile);

        // Enable DNSSEC, Donuts supports this
        parent::enableDnssec();


    }

}
