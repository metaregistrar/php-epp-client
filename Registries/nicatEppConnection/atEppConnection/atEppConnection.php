<?php

namespace Metaregistrar\EPP;
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 09.09.2015
 * Time: 10:56
 */
class atEppConnection extends nicatEppConnection {

    /*
    |--------------------------------------------------------------------------
    | atEppConnection
    |--------------------------------------------------------------------------
    |
    | Epp connection for TLD .at
    |
    */

    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);
        parent::setServices(array('urn:ietf:params:xml:ns:domain-1.0' => 'domain', 'urn:ietf:params:xml:ns:contact-1.0' => 'contact'));
        parent::enableDnssec();
        parent::addExtension('at-ext-epp', atEppConstants::namespaceAtExt);

        parent::addCommandResponse('Metaregistrar\EPP\atEppCreateContactRequest', 'Metaregistrar\EPP\atEppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppUpdateContactRequest', 'Metaregistrar\EPP\atEppUpdateContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppCreateDomainRequest', 'Metaregistrar\EPP\atEppCreateResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppUpdateDomainRequest', 'Metaregistrar\EPP\atEppUpdateDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppDeleteRequest', 'Metaregistrar\EPP\atEppDeleteResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppDeleteDomainRequest', 'Metaregistrar\EPP\eppDeleteResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppTransferRequest', 'Metaregistrar\EPP\atEppTransferResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\atEppInfoDomainResponse');
        parent::addCommandResponse('Metaregistrar\EPP\eppInfoContactRequest', 'Metaregistrar\EPP\atEppInfoContactResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppWithdrawRequest', 'Metaregistrar\EPP\atEppWithdrawResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppUndeleteRequest', 'Metaregistrar\EPP\atEppUndeleteResponse');
        parent::addCommandResponse('Metaregistrar\EPP\atEppPollRequest', 'Metaregistrar\EPP\atEppPollResponse');
    }
}
