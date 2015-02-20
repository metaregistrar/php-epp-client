<?php
namespace Metaregistrar\EPP;
#
# Load the SIDN specific additions
#
include_once(dirname(__FILE__) . '/amsterdamEppResponse.php');
include_once(dirname(__FILE__) . '/amsterdamEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/amsterdamEppCreateResponse.php');
include_once(dirname(__FILE__) . '/amsterdamEppPollRequest.php');
include_once(dirname(__FILE__) . '/amsterdamEppPollResponse.php');
include_once(dirname(__FILE__) . '/amsterdamEppCheckResponse.php');
include_once(dirname(__FILE__) . '/amsterdamEppInfoDomainResponse.php');
include_once(dirname(__FILE__) . '/amsterdamEppRenewRequest.php');

class amsterdamEppConnection extends eppConnection {

    public function __construct($logging = false) {
        parent::__construct($logging);
        if ($settings = $this->loadSettings(dirname(__FILE__))) {
            parent::setHostname($settings['hostname']);
            parent::setPort($settings['port']);
            parent::setUsername($settings['userid']);
            parent::setPassword($settings['password']);
        }
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::addExtension('sidn-epp-ext', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        parent::enableRgp();
        parent::enableDnssec();
        parent::addCommandResponse('Metaregistrar\EPP\amsterdamEppPollRequest', 'Metaregistrar\EPP\amsterdamEppPollResponse');
        parent::addCommandResponse('Metaregistrar\EPP\amsterdamEppCreateContactRequest', 'Metaregistrar\EPP\amsterdamEppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppCheckRequest', 'Metaregistrar\EPP\amsterdamEppCheckResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\amsterdamEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\amsterdamEppRenewRequest', 'Metaregistrar\EPP\eppRenewResponse');
    }

}
