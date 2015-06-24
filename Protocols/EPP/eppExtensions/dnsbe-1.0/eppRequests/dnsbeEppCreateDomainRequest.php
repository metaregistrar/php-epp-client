<?php
namespace Metaregistrar\EPP;
/*
    DNSBE supports hostattrs, but no hostobjs. This object forces the epp connection to use hostattrs
*/
class dnsbeEppCreateDomainRequest extends eppCreateDomainRequest {
    function __construct($createinfo) {

        if ($createinfo instanceof eppDomain) {
            $this->setForcehostattr(true);
            parent::__construct($createinfo);
        } else {
            throw new eppException('DNSBE does not support Host objects');
        }
        $this->addSessionId();
    }

}