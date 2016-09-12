<?php
namespace Metaregistrar\EPP;

class euridEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        //parent::enableDnssec();
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));
        //parent::useExtension('nsgroup-1.1');
        parent::useExtension('registrar-1.0');
        parent::useExtension('authInfo-1.0');
        #parent::addCommandResponse('euridEppCreateRequest', 'euridEppCreateResponse');
        #parent::addCommandResponse('euridEppAuthcodeRequest', 'eppResponse');
        #parent::addCommandResponse('euridEppCreateRequest', 'eppCreateResponse');
        #parent::addCommandResponse('eppCheckDomainRequest', 'euridEppCheckDomainResponse');
    }

}
