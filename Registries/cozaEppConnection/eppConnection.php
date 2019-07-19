<?php
namespace Metaregistrar\EPP;


class cozaEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));
        parent::useExtension('cozacontact-1.0');
        parent::useExtension('cozadomain-1.0');
        parent::useExtension('charge-1.0');
        parent::enableDnssec();
    }

}
