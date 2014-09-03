<?php

/**
 * EPP HTTPS connection
 *
 * Connects to EPP server through secure HTTP connection
 * Ip connection supports cert authentication cert file is used
 * (not tested). If cert file is not provided - ignore cert check
 *
 * @author Andrey
 */

include_once(dirname(__FILE__) . '/eppHttpConnection.php');

class eppHttpsConnection extends eppHttpConnection {

	/**
	 * Uses eppHttpConnection curl resource but adds
	 * secured protocol to URL and sets certificate
	 *
	 * @param $postMode bool
	 * @return null|resource
	 */

	protected function initCurl($postMode = true)
	{
		$ch = parent::initCurl($postMode);

		// Set secure protocol
		curl_setopt($ch, CURLOPT_URL, 'https://' . $this->getHostname());

		// If cert file is provided use cert
		if ($this->local_cert_path)
		{
			curl_setopt($ch, CURLOPT_SSLCERT, $this->local_cert_path);
			curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->local_cert_pwd);
		}
		else // Otherwise ignore cert check
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}

		return $ch;
	}

}

