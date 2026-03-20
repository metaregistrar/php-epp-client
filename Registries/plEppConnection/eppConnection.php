<?php

namespace Metaregistrar\EPP;

class plEppConnection extends eppHttpsConnection
{
    private $ca_path = null;

    public function __construct($logging = false, $settingsfile = null)
    {
        parent::__construct($logging, $settingsfile);

        // Set default namespace for this registry
        $this->addDefaultNamespace('http://www.dns.pl/nask-epp-schema/epp-2.1', 'xmlns', false);

        // Clear default services and add registry-specific services
        $this->setServices(array());
        $this->useExtension('pl-domain-2.1');
        $this->useExtension('pl-contact-2.1');
        $this->useExtension('pl-host-2.1');
        $this->useExtension('pl-future-2.1');

        // Add registry-specific EPP extensions
        $this->useExtension('pl-extcon-2.1');
        $this->useExtension('pl-extdom-2.1');
    }

    /**
     * Uses eppHttpConnection curl resource but adds
     * secured protocol to URL and sets certificate and certificate authority
     *
     * @param $postMode bool
     * @return null|resource
     */
    protected function initCurl($postMode = true)
    {
        /** @var \CurlHandle $ch */
        $ch = parent::initCurl($postMode);

        // Set secure protocol
        curl_setopt($ch, CURLOPT_URL, 'https://' . $this->getHostname());

        // Disable verbose output, debug log only request and response XML
        curl_setopt($ch, CURLOPT_VERBOSE, 0);

        // If cert file is provided use cert, .pl need also CA file.
        if ($this->local_cert_path) {
            curl_setopt($ch, CURLOPT_SSLCERT, $this->local_cert_path);
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->local_cert_pwd);
            curl_setopt($ch, CURLOPT_CAINFO, $this->ca_path);
        } else // Otherwise ignore cert check
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        return $ch;
    }

    /**
     * @param string $certificatepath
     * @param string | null $certificatepassword
     * @param bool $selfsigned
     * @param string | null $certificatekeypath
     * @param string | null $ceritificateauthoritypath
     *
     */
    public function enableCertification($certificatepath, $certificatepassword, $selfsigned = false, $certificatekeypath = null, $ceritificateauthoritypath = null)
    {
        $this->local_cert_path = $certificatepath;
        $this->local_cert_pwd = $certificatepassword;
        $this->allow_self_signed = $selfsigned;
        $this->local_pk_path = $certificatekeypath;
        $this->ca_path = $ceritificateauthoritypath;
    }

    public function disableCertification()
    {
        $this->local_cert_path = null;
        $this->local_cert_pwd = null;
        $this->allow_self_signed = null;
        $this->local_pk_path = null;
        $this->ca_path = null;
    }
}
