<?php

namespace Metaregistrar\EPP;

class rrpproxyEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::setCheckTransactionIds(false);
        parent::addExtension('keysys','http://www.key-systems.net/epp/keysys-1.0');
        parent::enableDnssec();
    }

}
