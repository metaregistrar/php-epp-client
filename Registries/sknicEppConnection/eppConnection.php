<?php
namespace Metaregistrar\EPP;

/**
 * Class sknicEppConnection
 * @package Metaregistrar\EPP
 *
 * This class is used to connect to the SK-NIC EPP server.
 * It extends the eppConnection class and sets the appropriate settings for SK-NIC.
 */
class sknicEppConnection extends eppConnection {
    /**
     * sknicEppConnection constructor.
     * @param bool $logging
     * @param null $settingsfile
     */
    public function __construct($logging = false, $settingsfile = null) {
        parent::__construct($logging, $settingsfile);

        parent::setLanguage('en'); // Allows only EN

        parent::enableDnssec();
        parent::enableRgp();

        parent::useExtension('sk-contact-ident-0.2');
        parent::useExtension('auxcontact-0.1');
    }
}
