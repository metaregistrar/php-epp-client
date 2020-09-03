<?php
namespace Metaregistrar\EPP;

class metaregInfoDomainResponse extends eppDnssecInfoDomainResponse {

    /**
     * Gets the domain command-ext-domain:autoRenew field as a boolean
     * as per Metaregistrar command-ext-domain-1.0.xsd
     *
     * @return bool autoRenew
     */
    public function getAutoRenew() {
        $val = $this->queryPath('/epp:epp/epp:response/epp:extension/command-ext-domain:extDomainInfData/command-ext-domain:autoRenew');
        if (!$val) {
            return false;
        }
        return strtolower($val) === 'true';
    }

    /**
     * Gets the domain command-ext-domain:autoRenewPeriod field as an integer
     * as per Metaregistrar command-ext-domain-1.0.xsd
     *
     * @return int autoRenewPeriod
     */
    public function getAutoRenewPeriod() {
        $val = $this->queryPath('/epp:epp/epp:response/epp:extension/command-ext-domain:extDomainInfData/command-ext-domain:autoRenewPeriod');
        if (!$val) {
            return false;
        }
        return intval($val);
    }

    /**
     * Gets the domain command-ext-domain:privacyOptions field as a boolean
     * as per Metaregistrar command-ext-domain-1.0.xsd
     *
     * @return bool privacyOptions
     */
    public function getPrivacy() {
        $val = $this->queryPath('/epp:epp/epp:response/epp:extension/command-ext-domain:extDomainInfData/command-ext-domain:privacy');
        if (!$val) {
            return false;
        }
        return strtolower($val) === 'true';
    }

}
