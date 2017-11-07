<?php
namespace Metaregistrar\EPP;


class dnsbeEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);

        #parent::addExtension('keygroup','http://www.dns.be/xml/epp/keygroup-1.0');
        parent::useExtension('nsgroup-1.0');
        parent::addExtension('registrar', 'http://www.dns.be/xml/epp/registrar-1.0');
        parent::useExtension('dnsbe-1.0');
        parent::enableDnssec();
    }

}
