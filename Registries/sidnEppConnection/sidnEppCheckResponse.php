<?php
namespace Metaregistrar\EPP;
/*
 *    <extension>
      <sidn-ext-epp:ext>
        <sidn-ext-epp:response>
          <sidn-ext-epp:msg code="F0019" field="Domain name">The specified value does not satisfy the expression ^[a-zA-Z0-9-.]*$.</sidn-ext-epp:msg>
          <sidn-ext-epp:msg code="F0018" field="Domain name">The specified value does not satisfy the expression ^.*[.][nN][lL]$.</sidn-ext-epp:msg>
        </sidn-ext-epp:response>
      </sidn-ext-epp:ext>
    </extension>

 */
class sidnEppCheckResponse extends eppCheckResponse {
    function __construct() {
        parent::__construct();
    }

    public function getCheckResults() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:response/sidn-ext-epp:msg');
        foreach ($result as $code) {
            $error[$code->getAttribute('code')] = array('field' => $code->getAttribute('field'), 'message' => $code->nodeValue);
        }
        return $error;
    }
}