<?php

namespace Metaregistrar\EPP;

/**
 * Class lvEppInfoDomainResponse
 * @package Metaregistrar\EPP
 */
class lvEppInfoDomainResponse extends eppInfoDomainResponse {
    /**
     * A helper function for retriving xpath query results.
     * @return mixed query result or null if missing
     */

    protected function getXpathQueryResult($query, $cast = null) {
        $xpath = $this->xPath();
        $result = $xpath->query($query);

        // Empty object
        if (! is_object($result) || $result->length == 0) {
            return null;
        }

        // Success
        $value = $result->item(0)->nodeValue;
        if ($cast) {
            settype($value, $cast);
        }
        
        return $value;
    }

    public function getLvDomainStatus() {
        return $this->getXpathQueryResult("/epp:epp/epp:response/epp:extension//*[name()='lvDomain:status']");
    }

    public function getSecondaryDomainStatus() {
        return $this->getXpathQueryResult("/epp:epp/epp:response/epp:resData/domain:infData/domain:status/@s");
    }
}
