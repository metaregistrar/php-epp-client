<?php

namespace Metaregistrar\EPP;

class itEppConnection extends eppHttpsConnection
{

    /*
    * Available credit in euros
    * @var float
    */
    protected float $credit = 0;

    public function __construct($logging = false, $settingsfile = null)
    {
        parent::__construct($logging, $settingsfile);

        parent::enableRgp();

        parent::setServices(
            array(
                'urn:ietf:params:xml:ns:domain-1.0' => 'domain',
                'urn:ietf:params:xml:ns:contact-1.0' => 'contact'
            )
        );

        // Add registry-specific EPP extensions
        parent::useExtension('it-extcon-1.0');
        parent::useExtension('it-extdom-2.0');
        parent::useExtension('it-extepp-2.0');
    }

    /**
     * Initialize cURL session
     * Disable verbose output if log is active
     * Prevents lot of data if used in console, XML data is still logged in log file
     *
     * @param bool $postMode
     * @return \CurlHandle
     */
    protected function initCurl($postMode = true)
    {
        /** @var \CurlHandle $ch */
        $ch = parent::initCurl($postMode);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);

        return $ch;
    }

    public function enableDnssec()
    {
        parent::enableDnssec();
        $this->useExtension('it-extsecdns-1.0');
    }

    public function disableDnssec()
    {
        parent::disableDnssec();
        $this->removeExtension('it-extsecdns-1.0');
    }

    /**
     * Performs an EPP login request and checks the result
     * @param bool $usecdata Enclose the password field with [[CDATA]]
     * @return bool
     */
    public function login($usecdata = false)
    {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }
        $login = new eppLoginRequest(null, $usecdata);

        if ($response = $this->request($login)) {
            // Get available credit
            /** @var itEppLoginResponse $response */
            $this->credit = $response->getCredit();

            $this->writeLog("Logged in", "LOGIN");
            $this->loggedin = true;
            return true;
        }
        return false;
    }

    /**
     * Get the available credit in euros
     * @return float
     */
    public function getCredit(): float
    {
        return $this->credit;
    }
}
