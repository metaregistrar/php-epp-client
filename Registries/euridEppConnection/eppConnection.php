<?php
namespace Metaregistrar\EPP;

class euridEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);

        parent::enableDnssec();
        parent::setServices(
            [
                'urn:ietf:params:xml:ns:domain-1.0' => 'domain',
                'urn:ietf:params:xml:ns:contact-1.0' => 'contact',
                'http://www.eurid.eu/xml/epp/registrarFinance-1.0' => 'registrar',
                'http://www.eurid.eu/xml/epp/nsgroup-1.1' => 'nsgroup',
            ]
        );
        parent::useExtension('authInfo-1.1');
        parent::useExtension('domain-ext-2.5');
        parent::useExtension('contact-ext-1.3');
        parent::useExtension('registrarFinance-1.0');
        parent::useExtension('poll-1.2');
        parent::useExtension('nsgroup-1.1');

        /* parse the eurid extensions */
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoContactRequest', 'Metaregistrar\EPP\euridEppInfoContactResponse');
    }
}
