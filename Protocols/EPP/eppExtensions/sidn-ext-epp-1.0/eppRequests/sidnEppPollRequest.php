<?php
namespace Metaregistrar\EPP;
class sidnEppPollRequest extends eppPollRequest {
    function __construct($polltype, $messageid = null) {
        parent::__construct($polltype, $messageid);
        $this->addExtension('xmlns:sidn-ext-epp', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
    }
}