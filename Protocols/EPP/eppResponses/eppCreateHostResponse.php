<?php
namespace Metaregistrar\EPP;

class eppCreateHostResponse extends eppResponse {
    /**
     * HOST CREATE RESPONSES
     */
    public function getHostName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:creData/host:name');
    }

    public function getHostCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:creData/host:crDate');
    }
}