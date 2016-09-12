<?php
namespace Metaregistrar\EPP;


class dnsbeEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::useExtension('nsgroup-1.0');
        parent::useExtension('registrar-1.0');
        parent::useExtension('dnsbe-1.0');
        #parent::useExtension('keygroup-1.0');

        parent::enableDnssec();

    }

}
