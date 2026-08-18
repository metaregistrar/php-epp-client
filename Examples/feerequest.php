<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\feeEppCheckDomainRequest;

if ($conn = eppConnection::create('')) {
	$conn->useExtension('fee-1.0');
	$domain = new eppDomain('mijndomein.nl');
	$feerequest = new feeEppCheckDomainRequest($domain);
	$feerequest->addFee('create','USD',2);
	$feerequest->addFee('renew');
	$feerequest->addFee('transfer');
	$feerequest->addFee('restore');

	$feerequest->dumpContents();
}
