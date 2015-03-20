<?php
namespace Metaregistrar\EPP;

/*
<extension>
  <iis:infData xmlns:iis="urn:se:iis:xml:epp:iis-1.2" xsi:schemaLocation="urn:se:iis:xml:epp:iis-1.2 iis-1.2.xsd">
      <iis:state>active</iis:state>
      <iis:clientDelete>0</iis:clientDelete>
  </iis:infData>
</extension>
 */

class iisEppInfoDomainResponse extends eppInfoDomainResponse {
    function __construct() {
        parent::__construct();
    }


    /**
     *
     * @return string State
     */
    public function getDomainState() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/iis:infData/iis:state');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }


    /**
     *
     * @return 0 or 1
     */
    public function getDomainClientDelete() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/iis:infData/iis:clientDelete');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     *
     * @return String
     */
    public function getDomainDeactDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/iis:infData/iis:deactDate');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }


    /**
     *
     * @return String
     */
    public function getDomainDelDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/iis:infData/iis:delDate');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}

