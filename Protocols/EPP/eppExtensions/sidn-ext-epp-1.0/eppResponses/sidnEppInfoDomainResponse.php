<?php
namespace Metaregistrar\EPP;

class sidnEppInfoDomainResponse extends eppInfoDomainResponse {
    function __construct() {
        parent::__construct();
    }


    /**
     *
     * @return string Period|null
     */
    public function getDomainPeriod() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:domain/sidn-ext-epp:period');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     *
     * @return string Optout|null
     */
    public function getDomainOptout() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:domain/sidn-ext-epp:optOut');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     *
     * @return string Limited|null
     */
    public function getDomainLimited() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:domain/sidn-ext-epp:limited');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     *
     * @return string DeleteDate|null
     */
    public function getDomainDeleteDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:infData/sidn-ext-epp:domain/sidn-ext-epp:scheduledDeleteDate');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}

