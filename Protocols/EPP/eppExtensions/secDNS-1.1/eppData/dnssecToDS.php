<?php
namespace Metaregistrar\EPP;
/**
 * Calculate ds-rdata from dnskey-rdata
 * For additional information please refer to RFC 5910: http://www.ietf.org/rfc/rfc5910.txt
 *
 * @param string owner, the coanonical name of the owner (e.g. example.com.)
 * @param int flags, the flags of the dnskey (only 256 or 257)
 * @param int protocol, the protocol of the dnskey (only 3)
 * @param int algoritm, the algorithm of the dnskey (only 3, 5, 6, 7, 8, 10, 12, 13 or 14)
 * @param string publickey, the full publickey base64 encoded (care, no spaces allowed)
 *

 * @return int < 0, on failure
 *   -1, unsupported owner
 *   -2, unsupported flags
 *   -3, unsupported protocol
 *   -4, unsupported algorithm
 *   -5, unsupported publickey
 */

class dnssecToDS {

	private string $domainname;
	private string $keytag;
	private string $algorithm;
	private string $digestsha1;
	private string $digestsha256;
	private array $algorithmnames = [
		1=>'RSA/MD5 (DEPRECATED)',
		2=>'Diffie-Hellman',
		3=>'DSA/SHA1',
		5=>'RSA/SHA-1',
		6=>'DSA-NSEC3-SHA1',
		7=>'RSASHA1-NSEC3-SHA1',
		8=>'RSA/SHA-256',
		10=>'RSA/SHA-512',
		12=>'GOST R 34.10-2001 (DEPRECATED)',
		13=>'ECDSA Curve P-256 with SHA-256',
		14=>'ECDSA Curve P-384 with SHA-384',
		15=>'Ed25519',
		16=>'Ed448',
		17=>'SM2 signing algorithm with SM3 hashing algorithm',
		23=>'GOST R 34.10-2012'
	];
	private array $algorithmcodes = [
		1=>'RSAMD5',
		2=>'DH',
		3=>'DSA',
		5=>'RSASHA1',
		6=>'DSA-NSEC3-SHA1',
		7=>'RSASHA1-NSEC3-SHA1',
		8=>'RSASHA256',
		10=>'RSASHA512',
		12=>'ECC-GOST',
		13=>'ECDSAP256SHA256',
		14=>'ECDSAP384SHA384',
		15=>'ED25519',
		16=>'ED448',
		17=>'SM2SM3',
		23=>'ECC-GOST12'
	];

	public function __construct($domainname, $flags, $protocol, $algorithm, $publickey) {
		// define paramenter check variants
		//$regex_domainname = '/^[a-z0-9\-]+\.[a-z]+\.[a-z]+\.$/';
		$allowed_flags = array(256, 257);
		$allowed_protocol = array(3);
		$allowed_algorithm = array(3, 5, 6, 7, 8, 10, 12, 13, 14);
		$regex_publickey = '/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=|[A-Za-z0-9+\/]{4})$/';

		// parameter checks and break if failed
		//if (!preg_match($regex_owner, $owner)) return -1;
		if (!in_array($flags, $allowed_flags)) return -2;
		if (!in_array($protocol, $allowed_protocol)) return -3;
		if (!in_array($algorithm, $allowed_algorithm)) return -4;
		if (!preg_match($regex_publickey, $publickey)) return -5;

		// calculate hex of parameters
		$owner_hex = '';
		$parts = explode(".", substr($domainname, 0, -1));
		foreach ($parts as $part) {
			$len = dechex(strlen($part));
			$owner_hex .= str_repeat('0', 2 - strlen($len)) . $len;
			$part = str_split($part);
			for ($i = 0; $i < count($part); $i++) {
				$byte = strtoupper(dechex(ord($part[$i])));
				$byte = str_repeat('0', 2 - strlen($byte)) . $byte;
				$owner_hex .= $byte;
			}
		}
		$owner_hex .= '00';
		$flags_hex = sprintf("%04x", $flags);
		$protocol_hex = sprintf("%02x", $protocol);
		$algorithm_hex = sprintf("%02x", $algorithm);
		$publickey_hex = bin2hex(base64_decode($publickey));

		// calculate keytag using algorithm defined in rfc
		$string = hex2bin($flags_hex . $protocol_hex . $algorithm_hex . $publickey_hex);
		$sum = 0;
		for ($i = 0; $i < strlen($string); $i++) {
			$b = ord($string[$i]);
			$sum += ($i & 1) ? $b : $b << 8;
		}
		$keytag = 0xffff & ($sum + ($sum >> 16));

		// calculate digest using rfc specified hashing algorithms
		$string = hex2bin($owner_hex . $flags_hex . $protocol_hex . $algorithm_hex . $publickey_hex);

		$digest_sha1 = strtoupper(hash('sha1', $string));
		$digest_sha256 = strtoupper(hash('sha256', $string));

		// return results and also copied parameters
		$this->domainname = $domainname;
		$this->keytag = $keytag;
		$this->algorithm = $algorithm;
		$this->digestsha1 = $digest_sha1;
		$this->digestsha256 = $digest_sha256;
	}

	public function getdomainname() {
		return $this->domainname;
	}

	public function getkeytag() {
		return $this->keytag;
	}

	public function getalgorithm() {
		return $this->algorithm;
	}

	public function getalgorithmname() {
		if (isset($this->algorithmnames[$this->algorithm])) {
			return $this->algorithmnames[$this->algorithm];
		}
		return null;
	}

	public function getalgorithmcode() {
		if (isset($this->algorithmcodes[$this->algorithm])) {
			return $this->algorithmcodes[$this->algorithm];
		}
		return null;
	}


	public function getdigestsha1() {
		return $this->digestsha1;
	}

	public function getdigestsha256() {
		return $this->digestsha256;
	}
}