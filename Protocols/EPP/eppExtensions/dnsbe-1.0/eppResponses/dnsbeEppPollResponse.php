<?php

namespace Metaregistrar\EPP;

class dnsbeEppPollResponse extends eppPollResponse
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPollResAction()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:action');
    }

    public function getPollResDomainname()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:domainname');
    }

    public function getPollResReturncode()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:returncode');
    }

    public function getPollResType()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:type');
    }

    public function getContact()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:contact');
    }

}
