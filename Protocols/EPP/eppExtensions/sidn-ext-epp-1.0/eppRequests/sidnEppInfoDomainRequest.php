<?php
namespace Metaregistrar\EPP;

class sidnEppInfoDomainRequest extends eppInfoDomainRequest {
    function __construct($infodomain, $hosts=null, $namespacesinroot=true) {
        parent::__construct($infodomain, $hosts, $namespacesinroot);
    }
}