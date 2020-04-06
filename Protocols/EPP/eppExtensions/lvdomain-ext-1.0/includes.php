<?php
$this->addExtension('lv-domain-ext-1.0', 'http://www.nic.lv/epp/schema/lvdomain-ext-1.0');

include_once(dirname(__FILE__) . '/eppRequests/lvEppUpdateDomainRenewStatusRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\lvEppUpdateDomainRenewStatusRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppResponse/lvEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\lvEppInfoDomainResponse');