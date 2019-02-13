<?php
namespace Metaregistrar\EPP;


class cozaEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::useExtension('cozacontact-1.0');
        parent::useExtension('charge-1.0');
        parent::enableDnssec();
    }

}
