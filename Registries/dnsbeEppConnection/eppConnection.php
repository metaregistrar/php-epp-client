<?php
namespace Metaregistrar\EPP;
#
# Load the DNSBE specific additions
#
include_once(dirname(__FILE__) . '/dnsbeEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/dnsbeEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/dnsbeEppCreateResponse.php');
include_once(dirname(__FILE__) . '/dnsbeEppCreateNsgroupRequest.php');
include_once(dirname(__FILE__) . '/dnsbeEppCreateNsgroupResponse.php');
include_once(dirname(__FILE__) . '/dnsbeEppAuthcodeRequest.php');
include_once(dirname(__FILE__) . '/dnsbeEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/dnsbeEppInfoDomainResponse.php');
include_once(dirname(__FILE__) . '/dnsbeEppTransferRequest.php');

class dnsbeEppConnection extends eppConnection
{

    public function __construct($logging=false)
    {
        parent::__construct($logging);
        $settings = $this->loadSettings(dirname(__FILE__));
        parent::setHostname($settings['hostname']);
        parent::setPort($settings['port']);
        parent::setUsername($settings['userid']);
        parent::setPassword($settings['password']);
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::addExtension('nsgroup','http://www.dns.be/xml/epp/nsgroup-1.0');
        parent::addExtension('registrar','http://www.dns.be/xml/epp/registrar-1.0');
        parent::addExtension('dnsbe','http://www.dns.be/xml/epp/dnsbe-1.0');
        parent::enableDnssec();
        #parent::addExtension('keygroup','http://www.dns.be/xml/epp/keygroup-1.0');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateNsgroupRequest', 'Metaregistrar\EPP\dnsbeEppCreateNsgroupResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateDomainRequest', 'Metaregistrar\EPP\dnsbeEppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateContactRequest', 'Metaregistrar\EPP\dnsbeEppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppAuthcodeRequest', 'Metaregistrar\EPP\eppResponse');
        parent::addCommandResponse('Metaregistrar\EPP\dnsbeEppInfoDomainRequest', 'Metaregistrar\EPP\dnsbeEppInfoDomainResponse');
    }

}
