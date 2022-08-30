<?php

namespace Metaregistrar\EPP;

class bnamedEppConnection extends eppConnection {

    function __construct($logging = false, $settingsFile = null) {
        // Construct the EPP connection object
        parent::__construct($logging, $settingsFile);
        parent::enableDnssec();
        // Add registry-specific EPP extensions
        parent::useExtension('bnamed-1.0');
    }

    public function addCommandResponse($command, $response) {
        parent::addCommandResponse($command, $response);
    }

}
