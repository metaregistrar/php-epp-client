<?php
namespace Metaregistrar\EPP;

/*
 *  <extension>
      <keysys:resData xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
        <keysys:contactInfData>
          <keysys:validated>1</keysys:validated>
          <keysys:verified>1</keysys:verified>
        </keysys:contactInfData>
      </keysys:resData>
    </extension>
 */

class rrpproxyEppInfoContactResponse extends eppInfoContactResponse {
    function __construct() {
        parent::__construct();
    }


    /**
     *
     * @return bool|null
     */
    public function getValidated() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:contactInfData/keysys:validated');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue == '1' ? true : false;
        } else {
            return null;
        }
    }

    /**
     *
     * @return bool|null
     */
    public function getVerified() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:contactInfData/keysys:verified');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue == '1' ? true : false;
        } else {
            return null;
        }
    }
}

