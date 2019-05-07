<?php
namespace Metaregistrar\EPP;
/**
 * <epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:sidn-ext-epp="http://rxsd.domain-registry.nl/sidn-ext-epp-1.0">
 *   <response>
 *     <result code="2200">
 *       <msg>Validation of the transaction failed.</msg>
 *     </result>
 *     <extension>
 *       <sidn-ext-epp:ext>
 *         <sidn-ext-epp:response>
 *           <sidn-ext-epp:msg code="C0033a" field="New password">Invalid password: a password must contain a minimum of 10 characters.</sidn-ext-epp:msg>
 *         </sidn-ext-epp:response>
 *       </sidn-ext-epp:ext>
 *     </extension>
 *     <trID>
 *       <clTRID>5b08a3e6497d8</clTRID>
 *       <svTRID>D3074A84-6928-DA21-1790-A50F2AC086EC</svTRID>
 *     </trID>
 *   </response>
 * </epp>
 */
/**
 * Class sidnEppException
 * @package Metaregistrar\EPP
 */
class sidnEppException extends eppException {

    /**
     * @var eppResponse
     */
    private $eppresponse;

    public function __construct($message = "", $code = 0, \Exception $previous = null, $reason = null, $command = null) {
        if ($command) {
            $this->eppresponse = new eppResponse();
            $this->eppresponse->loadXML($command);
        }
        parent::__construct($message, $code, $previous, $reason, $command);
    }

    public function getSidnErrorCode() {
        return $this->eppresponse->queryPath('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:response/sidn-ext-epp:msg/@code');
    }

    public function getSidnErrorMessage() {
        return $this->eppresponse->queryPath('/epp:epp/epp:response/epp:extension/sidn-ext-epp:ext/sidn-ext-epp:response/sidn-ext-epp:msg');
    }
}