<?php

namespace Metaregistrar\EPP;

class itEppLoginResponse extends eppResponse
{
    /**
     * Return the available credit in euros
     * @return float
     */
    public function getCredit()
    {
        return (float) $this->queryPath('/epp:epp/epp:response/epp:extension/extepp:creditMsgData/extepp:credit');
    }
}