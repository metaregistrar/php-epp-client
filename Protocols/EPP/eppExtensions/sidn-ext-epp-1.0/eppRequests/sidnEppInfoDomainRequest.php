<?php
namespace Metaregistrar\EPP;

class sidnEppInfoDomainRequest extends eppInfoDomainRequest {
    function __construct($infodomain, $hosts, $namespacesinroot) {
        parent::__construct($infodomain, $hosts, $namespacesinroot);
    }
}