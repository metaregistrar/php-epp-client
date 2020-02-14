<?php
namespace Metaregistrar\EPP;

    /**
     *
    <epp:epp xmlns:epp="urn:ietf:params:xml:ns:epp-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:cozacontact="http://co.za/epp/extensions/cozacontact-1-0">
        <epp:response>
            <epp:extension>
                <cozacontact:infData>
                    <cozacontact:domain level="Contact">exampledomain1.zoneza</cozacontact:domain>
                    <cozacontact:domain level="Contact">exampledomain2.zoneza</cozacontact:domain>
                </cozacontact:infData>
            </epp:extension>
        </epp:response>
    </epp:epp>
     */

/**
 * Class cozaEppInfoContactResponse
 * @package Metaregistrar\EPP
 */
class cozaEppInfoContactResponse extends eppInfoContactResponse {

    function __construct() {
        parent::__construct();
    }


    /**
     * Retrieve an array of domain names associated with this contact
     * @return array|null
     */
    public function getContactDomains() {
        $domainlist = [];
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/cozacontact:infData/*');
        if ($result->length > 0) {
            foreach ($result as $record) {
                /* @var $record \DOMElement */
                $domainlist[] = ['level'=>$record->getAttribute('level'),'domainname'=>$record->nodeValue];
            }
            return $domainlist;
        } else {
            return null;
        }
    }
}