<?php
namespace Metaregistrar\EPP;

/**

<epp:epp xmlns:epp="urn:ietf:params:xml:ns:epp-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:cozacontact="http://co.za/epp/extensions/cozacontact-1-0">
    <epp:response>
        <epp:result code="1000">
            <epp:msg>Contact Info Command completed successfully</epp:msg>
        </epp:result>
        <epp:resData>
            <contact:infData>
                <contact:id>RegistrarID</contact:id>
                <contact:roid>ABC-123</contact:roid>
                <contact:status s="ok"/>
                <contact:voice/>
                <contact:fax/>
                <contact:email>registraremail@example.com</contact:email>
                <contact:clID/>
                <contact:crID/>
                <contact:crDate>2011-02-07T11:02:55Z</contact:crDate>
            </contact:infData>
        </epp:resData>
        <epp:extension>
            <cozacontact:infData>
                <cozacontact:balance>1111.11</cozacontact:balance>
            </cozacontact:infData>
        </epp:extension>
        <epp:trID>
            <epp:svTRID>ZACR-12E75BD4F95-948A5</epp:svTRID>
        </epp:trID>
    </epp:response>
</epp:epp>
 */

/**
 * Class cozaEppInfoContactResponse
 * @package Metaregistrar\EPP
 */
class cozaEppBalanceCheckResponse extends eppInfoContactResponse {

    function __construct() {
        parent::__construct();
    }


    /**
     * Retrieve an array of domain names associated with this contact
     * @return array|null
     */
    public function getBalance() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/cozac:infData/cozac:balance');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}