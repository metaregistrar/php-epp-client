<?php
$this->addExtension('extepp-2.0', 'http://www.nic.it/ITNIC-EPP/extepp-2.0');

include_once(dirname(__FILE__) . '/eppResponses/itEppLoginResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\eppLoginRequest', 'Metaregistrar\EPP\itEppLoginResponse');
