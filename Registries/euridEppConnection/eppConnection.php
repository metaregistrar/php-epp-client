<?php
namespace Metaregistrar\EPP;

class euridEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);

        parent::enableDnssec();
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact','http://www.eurid.eu/xml/epp/registrarFinance-1.0'=>'registrar'));
        parent::useExtension('authInfo-1.1');
        parent::useExtension('domain-ext-2.1');
        parent::useExtension('contact-ext-1.1');
        parent::useExtension('registrarFinance-1.0');

    }

}
