<?php

namespace Metaregistrar\EPP;

class dnsbeEppPollResponse extends eppPollResponse
{
    public function getAction()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:action');
    }

    public function getDomainname()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:domainname');
    }

    public function getReturncode()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:returncode');
    }

    public function getType()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:type');
    }

    public function getContact()
    {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/dnsbe:pollRes/dnsbe:contact');
    }
}
