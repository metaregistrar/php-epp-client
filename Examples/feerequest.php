<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;

if ($conn = eppConnection::create('')) {
	$conn->useExtension('fee-1.0');
	$domain = new eppDomain('mijndomein.nl');
	$feerequest = new \Metaregistrar\EPP\feeEppCheckDomainRequest($domain);
	$feerequest->addFee('create','USD',2);
	$feerequest->addFee('renew');
	$feerequest->addFee('transfer');
	$feerequest->addFee('restore');

	$feerequest->dumpContents();
}
