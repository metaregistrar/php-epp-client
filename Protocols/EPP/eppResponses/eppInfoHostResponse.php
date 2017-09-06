<?php
namespace Metaregistrar\EPP;

class eppInfoHostResponse extends eppInfoResponse {

    /**
     *
     * @return eppHost
     */
    public function getHost() {
        $hostname = $this->getHostName();
        $address = $this->getHostAddresses();
        $address = array_keys($address);
        $host = new eppHost($hostname, $address);
        return $host;
    }

    /**
     *
     * @return string hostname
     */
    public function getHostName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:name');
    }

    /**
     *
     * @return array of host addresses
     */
    public function getHostAddresses() {
        $ip = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:addr');
        foreach ($result as $address) {
            /* @var $address \DOMElement */
            $ip[$address->nodeValue] = $address->getAttribute('ip');
        }
        return $ip;
    }

    /**
     *
     * @return string status
     */
    public function getHostStatuses() {
        $stat = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:status/@s');
        foreach ($result as $status) {
            $stat[] = $status->nodeValue;
        }
        return $stat;
    }

    /**
     *
     * @return string statuses
     */
    public function getHostStatusCSV() {
        return parent::arrayToCSV($this->getHostStatuses());
    }

    /**
     *
     * @return string roid
     */
    public function getHostRoid() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:roid');
    }

    /**
     *
     * @return string create_date
     */
    public function getHostCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:crDate');
    }

    /**
     *
     * @return string update_date
     */
    public function getHostUpdateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:upDate');
    }

    /**
     *
     * @return string client id
     */
    public function getHostClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:clID');
    }

    /**
     *
     * @return string client id
     */
    public function getHostCreateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:crID');
    }

    /**
     *
     * @return string client id
     */
    public function getHostUpdateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:upID');
    }
}
