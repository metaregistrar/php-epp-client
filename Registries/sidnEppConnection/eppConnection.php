<?php
namespace Metaregistrar\EPP;

class sidnEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::enableDnssec();
        parent::useExtension('sidn-ext-epp-1.0');
    }

}
