<?php

namespace Metaregistrar\EPP;

/**
 * <extension>
 * <keysys:resData xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
 * <keysys:creData>
 * <keysys:validated>1</keysys:validated>
 * <keysys:verified>1</keysys:verified>
 * </keysys:creData>
 * </keysys:resData>
 * </extension>
 */
class rrpproxyEppCreateContactResponse extends eppCreateContactResponse {
    /**
     * Initializes the constructor for the class by calling the parent constructor.
     *
     * @return void
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Destructor method that is automatically called when the object is destroyed.
     * Ensures proper cleanup by invoking the parent class's destructor.
     *
     * @return void
     */
    function __destruct() {
        parent::__destruct();
    }

    /**
     * Retrieves the value of the 'validated' node from the XML response, if available.
     *
     * @return int|null The value of the 'validated' node as an integer, or null if the node does not exist.
     */
    public function getValidated() : ?int {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:creData/keysys:validated');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Retrieves the value of the 'verified' node from the XML response, if available.
     *
     * @return int|null The value of the 'verified' node as an integer, or null if the node does not exist.
     */
    public function getVerified() : ?int {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:creData/keysys:verified');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        }
        return null;
    }

    /**
     * Retrieves the value of the verification-requested node from the XML response.
     * NOTE: No longer seems supported but referenced in documentation
     *
     * @return int|null Returns the verification-requested value as an integer if it exists, or null if not found.
     */
    public function getVerificationRequested() : ?int {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/keysys:resData/keysys:creData/keysys:verification-requested');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        }
        return null;
    }
}
