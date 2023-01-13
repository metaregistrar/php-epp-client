<?php
namespace Metaregistrar\EPP;
/**
 * Class euridEppInfoDomainResponse
 * @package Metaregistrar\EPP
 *
 *
 * <extension>
<domain-ext-2.3:infData>
<domain-ext-2.3:onHold>false</domain-ext-2.3:onHold>
<domain-ext-2.3:quarantined>true</domain-ext-2.3:quarantined>
<domain-ext-2.3:suspended>false</domain-ext-2.3:suspended>
<domain-ext-2.3:seized>false</domain-ext-2.3:seized>
<domain-ext-2.3:availableDate>2021-07-11T10:35:00.000Z</domain-ext-2.3:availableDate>
<domain-ext-2.3:deletionDate>2021-06-01T10:35:05.113Z</domain-ext-2.3:deletionDate>
 */

class euridEppInfoDomainResponse extends eppInfoDomainResponse {
    function __construct() {
        parent::__construct();
    }

    /**
     *
     * @return boolean|null
     */
    public function getQuarantined() {
        $xpath = $this->xPath();
        $result = @$xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:quarantined');
        if (is_object($result) && $result->length > 0) {
            if ($result->item(0)->nodeValue == 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }


    /**
     *
     * @return boolean|null
     */
    public function getOnHold() {
        $xpath = $this->xPath();
        $result = @$xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:onhold');
        if (is_object($result) && $result->length > 0) {
            if ($result->item(0)->nodeValue == 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    /**
     *
     * @return boolean|null
     */
    public function getSuspended() {
        $xpath = $this->xPath();
        $result = @$xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:suspended');
        if (is_object($result) && $result->length > 0) {
            if ($result->item(0)->nodeValue == 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    /**
     *
     * @return boolean|null
     */
    public function getSeized() {
        $xpath = $this->xPath();
        $result = @$xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:seized');
        if (is_object($result) && $result->length > 0) {
            if ($result->item(0)->nodeValue == 'true') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    /**
     *
     * @return string|null
     */
    public function getAvailableDate() {
        $xpath = $this->xPath();
        $result = @$xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:availableDate');
        if (is_object($result) && $result->length > 0) {
            return (Date("Y-m-d",strtotime($result->item(0)->nodeValue)));
        } else {
            return null;
        }
    }

    /**
     *
     * @return string|null
     */
    public function getDeletionDate() {
        $xpath = $this->xPath();
        $result = @$xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:deletionDate');
        if (is_object($result) && $result->length > 0) {
            return (Date("Y-m-d",strtotime($result->item(0)->nodeValue)));
        } else {
            return null;
        }
    }

    /**
     *
     * @return string|null
     */
    public function getMaxExtensionPeriod() {
        $xpath = $this->xPath();
        $result = @$xpath->query('/epp:epp/epp:response/epp:extension/domain-ext:infData/domain-ext:maxExtensionPeriod');
        if (is_object($result) && $result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}
