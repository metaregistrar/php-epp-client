<?php
namespace Metaregistrar\EPP;


class dnsbeEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::addExtension('nsgroup', 'http://www.dns.be/xml/epp/nsgroup-1.0');
        parent::addExtension('registrar', 'http://www.dns.be/xml/epp/registrar-1.0');
        parent::addExtension('dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        #parent::addExtension('keygroup','http://www.dns.be/xml/epp/keygroup-1.0');

        parent::enableDnssec();

        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateNsgroupRequest', 'Metaregistrar\EPP\dnsbeEppCreateNsgroupResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateDomainRequest', 'Metaregistrar\EPP\dnsbeEppCreateDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateContactRequest', 'Metaregistrar\EPP\dnsbeEppCreateContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppAuthcodeRequest', 'Metaregistrar\EPP\eppResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppInfoDomainRequest', 'Metaregistrar\EPP\dnsbeEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppDeleteDomainRequest', 'Metaregistrar\EPP\dnsbeEppDeleteDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppUpdateContactRequest', 'Metaregistrar\EPP\dnsbeEppUpdateContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateNsgroupRequest', 'Metaregistrar\EPP\dnsbeEppCreateNsgroupResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppTransferRequest', 'Metaregistrar\EPP\eppTransferResponse');
    }

}
