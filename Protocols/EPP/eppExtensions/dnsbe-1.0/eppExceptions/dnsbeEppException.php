<?php

namespace Metaregistrar\EPP;

use Exception;

/**
* <epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:dnsbe="http://www.dns.be/xml/epp/dnsbe-1.0">
*   <response>
*     <result code="2005">
*       <msg>Parameter value syntax error</msg>
*     </result>
*     <extension>
*       <dnsbe:ext>
*         <dnsbe:result>
*           <dnsbe:msg>missing glue for ns.test-domain-1.be</dnsbe:msg>
*         </dnsbe:result>
*       </dnsbe:ext>
*     </extension>
*     <trID>
*       <clTRID>client-00018</clTRID>
*       <svTRID>dnsbe-113</svTRID>
*     </trID>
*   </response>
* </epp>
*/
/**
 * Class dnsbeEppException.
 */
class dnsbeEppException extends eppException
{
    /**
     * @var eppResponse
     */
    private $eppresponse;

    public function __construct($message = '', $code = 0, ?Exception $previous = null, $reason = null, $command = null)
    {
        if ($command) {
            $this->eppresponse = new eppResponse();
            $this->eppresponse->loadXML($command);
        }
        parent::__construct($message, $code, $previous, $reason, $command);
    }

    public function getDnsbeErrorMessage()
    {
        return $this->eppresponse->queryPath('/epp:epp/epp:response/epp:extension/dnsbe:ext/dnsbe:result/dnsbe:msg');
    }
}
