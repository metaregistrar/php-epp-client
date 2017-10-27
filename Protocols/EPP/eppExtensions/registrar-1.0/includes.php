<?php
$this->addService('http://www.eurid.eu/xml/epp/registrar-1.0', 'registrar');
#
# For use with the EURID connection
#
include_once(dirname(__FILE__) . '/eppRequests/euridEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar/EPP/euridEppInfoDomainRequest', 'Metaregistrar/EPP/euridEppInfoDomainResponse');

#parent::addCommandResponse('euridEppCreateRequest', 'euridEppCreateResponse');
#parent::addCommandResponse('euridEppAuthcodeRequest', 'eppResponse');

#parent::addCommandResponse('euridEppCreateRequest', 'eppCreateResponse');
#parent::addCommandResponse('eppCheckRequest', 'euridEppCheckResponse');
