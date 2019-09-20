<?php
namespace Metaregistrar\EPP;

    /**
    <epp:epp xmlns:epp="urn:ietf:params:xml:ns:epp-1.0" xmlns:cozadomain="http://co.za/epp/extensions/cozadomain-1-0">
      <epp:response>
        <epp:result code="1001">
          <epp:msg>Domain update pending</epp:msg>
        </epp:result>
        <epp:extension>
          <cozadomain:cozaData>
            <cozadomain:detail result="success">AutoRenew 'True' successful</cozadomain:detail>
          </cozadomain:cozaData>
        </epp:extension>
      <epp:trID>
      <epp:svTRID>ZACR-EPP-12E52F2BC78-8AC51</epp:svTRID>
      </epp:trID>
    </epp:response>
    </epp:epp>
     */

/**
 * Class cozaEppInfoContactResponse
 * @package Metaregistrar\EPP
 */
class cozaEppAutorenewResponse extends eppUpdateDomainResponse
{

    function __construct() {
        parent::__construct();
    }

    /**
     * Retrieve the response for the autorenew request
     * @return string|null
     */
    public function getAutorenewResult() {
        return $this->queryPath('/epp:epp/epp:response/epp:extension/cozadomain:cozaData/cozadomain:detail');
    }
}