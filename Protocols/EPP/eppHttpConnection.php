<?php

/**
 * EPP connection over HTTP protocol
 * Uses CURL library to communicate with server
 *
 * @author Andrey
 */

include_once(dirname(__FILE__).'eppConnection.php');

class eppHttpConnection extends EppConnection {

	/**
	 * CURL resource
	 *
	 * @var null|resource
	 */

	protected $ch = null;

	/**
	 * Response
	 *
	 * @var string
	 */

	private $response = null;

	/**
	 * No need to connect
	 *
	 * @param null $hostname
	 * @param null $port
	 * @return bool
	 */

	public function connect($hostname = null, $port = null)
	{
		return true;
	}

	/**
	 * No need to disconnect
	 *
	 * @return bool
	 */

	public function disconnect()
	{
		return true;
	}

	public function __destruct()
	{
		parent::__destruct();
		if ($this->ch)
		{
			curl_close($this->ch);
		}
	}

	/**
	 * Initializes CURL connection.
	 *
	 * Initialized connection will use cookies
	 *
	 * @param $postMode bool
	 * @return null|resource
	 */

	protected function initCurl($postMode = true)
	{
		if ($this->ch === null) {
			$ch = curl_init('http://' . $this->getHostname());

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			// Logging mode - set curl to verbose
			if ($this->logging)
			{
				curl_setopt($ch, CURLOPT_VERBOSE, 1);
			}

			if ($postMode)
			{
				curl_setopt($ch, CURLOPT_POST, 1);
			}

			if ($this->timeout)
			{
				curl_setopt($ch, CURLOPT_POST, CURLOPT_CONNECTTIMEOUT);
			}

			// Set cookie file
			curl_setopt($ch, CURLOPT_COOKIE, true);
			curl_setopt($ch, CURLOPT_COOKIEFILE, tmpfile());

			$this->ch = $ch;
		}

		return $this->ch;
	}

	/**
	 * Write to CURL resource
	 *
	 * @param string $content
	 * @return bool
	 * @throws eppException
	 */

	public function write($content)
	{
		$this->writeLog("Writing: ".strlen($content));

		$ch = $this->initCurl();
		if (!is_resource($ch))
		{
			throw new eppException('Failed to init CURL resource.');
		}

		$this->writeLog($content);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		$response = curl_exec($ch);

		$error = curl_errno($ch);

		if ($error)
		{
			throw new eppException(sprintf('Error occurred while executing CURL %d: %s', $error, curl_error($ch)));
		}

		$this->response = $response;
		return true;
	}

	/**
	 * Read CURL response
	 *
	 * @return string
	 * @throws eppException
	 */

	public function read()
	{
		$this->writeLog("Reading getting response.");

		if ($this->response === null)
		{
			throw new eppException('Response is empty. Could be reading without writing.');
		}

		$response = $this->response;
		$this->response = null;

		return $response;
	}


}

