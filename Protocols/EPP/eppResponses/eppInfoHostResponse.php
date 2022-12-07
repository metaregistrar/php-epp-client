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
     * Get the hostname from the server response
     * @return string
     */
    public function getHostName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:name');
    }

    /**
     * Get Host IP addresses from the server response
     * @return array
     */
    public function getHostAddresses() {
        $ip = [];
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/host:infData/host:addr');
        foreach ($result as $address) {
            /* @var $address \DOMElement */
            $ip[$address->nodeValue] = $address->getAttribute('ip');
        }
        return $ip;
    }

    /**
     * Get an array of statuses from the server response
     * @return null|string[]
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
     * Get statuses as comma-separated values from the server response
     * @return string
     */
    public function getHostStatusCSV() {
        return parent::arrayToCSV($this->getHostStatuses());
    }

    /**
     *
     * @return string
     */
    public function getHostRoid() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:roid');
    }

    /**
     *
     * @return string
     */
    public function getHostCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:crDate');
    }

    /**
     *
     * @return string
     */
    public function getHostUpdateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:upDate');
    }

    /**
     *
     * @return string
     */
    public function getHostClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:clID');
    }

    /**
     *
     * @return string
     */
    public function getHostCreateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:crID');
    }

    /**
     *
     * @return string
     */
    public function getHostUpdateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/host:infData/host:upID');
    }
}
