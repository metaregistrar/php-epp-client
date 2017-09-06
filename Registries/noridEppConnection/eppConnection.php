<?php
namespace Metaregistrar\EPP;

class noridEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsFile = null) {
        // Construct the EPP connection object
        parent::__construct($logging, $settingsFile);

        // Enable DNSSEC because Norid supports this
        parent::enableDnssec();

        // Define supported EPP services
        parent::setServices(array(
            'urn:ietf:params:xml:ns:domain-1.0' => 'domain',
            'urn:ietf:params:xml:ns:contact-1.0' => 'contact',
            'urn:ietf:params:xml:ns:host-1.0' => 'host'
        ));

        // Add registry-specific EPP extensions
        // parent::addExtension('secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1'); // This is done by parent::enableDnssec();
        parent::addExtension('no-ext-epp', 'http://www.norid.no/xsd/no-ext-epp-1.0');
        parent::addExtension('no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');
        parent::addExtension('no-ext-contact', 'http://www.norid.no/xsd/no-ext-contact-1.0');
        parent::addExtension('no-ext-host', 'http://www.norid.no/xsd/no-ext-host-1.0');
        parent::addExtension('no-ext-result', 'http://www.norid.no/xsd/no-ext-result-1.0');

        // Add registry-specific EPP command requests/responses

        // Domain Create/Withdraw
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppCreateDomainRequest', 'Metaregistrar\\EPP\\noridEppCreateDomainResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppWithdrawDomainRequest', 'Metaregistrar\\EPP\\noridEppWithdrawDomainResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppTransferRequest', 'Metaregistrar\\EPP\\noridEppTransferResponse');

        // Contact Create/Check/Info/Update
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppCreateContactRequest', 'Metaregistrar\\EPP\\noridEppCreateContactResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\eppCheckContactRequest', 'Metaregistrar\\EPP\\noridEppCheckContactResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\eppInfoContactRequest', 'Metaregistrar\\EPP\\noridEppInfoContactResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppUpdateContactRequest', 'Metaregistrar\\EPP\\noridEppUpdateContactResponse');
        
        // Host Create/Info/Update/Delete
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppCreateHostRequest', 'Metaregistrar\\EPP\\noridEppCreateHostResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppInfoHostRequest', 'Metaregistrar\\EPP\\noridEppInfoHostResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\noridEppUpdateHostRequest', 'Metaregistrar\\EPP\\noridEppUpdateHostResponse');
        parent::addCommandResponse('Metaregistrar\\EPP\\eppDeleteHostRequest', 'Metaregistrar\\EPP\\noridEppDeleteHostResponse');
    }

}