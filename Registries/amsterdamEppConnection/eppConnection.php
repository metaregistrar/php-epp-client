<?php
namespace Metaregistrar\EPP;

class amsterdamEppConnection extends eppConnection {

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::addExtension('sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        parent::enableRgp();
        parent::enableDnssec();
        parent::addCommandResponse('Metaregistrar\EPP\sidnEppPollRequest', 'Metaregistrar\EPP\sidnEppPollResponse');
        parent::addCommandResponse('Metaregistrar\EPP\sidnEppCreateContactRequest', 'Metaregistrar\EPP\sidnEppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppCheckRequest', 'Metaregistrar\EPP\sidnEppCheckResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\sidnEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\sidnEppRenewRequest', 'Metaregistrar\EPP\eppRenewResponse');
    }

}
