<?php
namespace Metaregistrar\EPP;
/*
   <?xml version="1.0" encoding="UTF-8" standalone="no"?>
   <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
     <response>
       <result code="1301">
         <msg>Command completed successfully; ack to dequeue</msg>
       </result>
       <msgQ count="5" id="12345">
         <qDate>2000-06-08T22:00:00.0Z</qDate>
         <msg>Transfer requested.</msg>
       </msgQ>
       <resData>
         <obj:trnData
          xmlns:obj="urn:ietf:params:xml:ns:obj-1.0">
           <obj:name>example.com</obj:name>
           <obj:trStatus>pending</obj:trStatus>
           <obj:reID>ClientX</obj:reID>
           <obj:reDate>2000-06-08T22:00:00.0Z</obj:reDate>
           <obj:acID>ClientY</obj:acID>
           <obj:acDate>2000-06-13T22:00:00.0Z</obj:acDate>
           <obj:exDate>2002-09-08T22:00:00.0Z</obj:exDate>
         </obj:trnData>
       </resData>
       <trID>
         <clTRID>ABC-12345</clTRID>
         <svTRID>54321-XYZ</svTRID>
       </trID>
     </response>
   </epp>
 */

class eppPollResponse extends eppResponse {
    const TYPE_TRANSFER = 'trn';
    const TYPE_CREATE = 'cre';
    const TYPE_INFO = 'inf';
    const TYPE_PAN = 'pan';
    const TYPE_CHECK = 'chk';
    const TYPE_RENEW = 'ren';
    const TYPE_UNKNOWN = 'unknown';

    private $messageType = null;

    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }


    /**
     * Return the identifier of the message
     * Use this identifier to acknowledge the poll message
     * @return null|string
     */
    public function getMessageId() {
        return $this->queryPath('/epp:epp/epp:response/epp:msgQ/@id');
    }

    /**
     * Return the date of the message
     * @return null|string
     */
    public function getMessageDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:msgQ/epp:qDate');
    }

    /**
     * Return the poll message
     * @return null|string
     */
    public function getMessage() {
        return $this->queryPath('/epp:epp/epp:response/epp:msgQ/epp:msg');
    }

    /**
     * return the number of messages that remain
     * @return int|string
     */
    public function getMessageCount() {
        if ($this->getResultCode() == eppResponse::RESULT_NO_MESSAGES) {
            return 0;
        } else {
            return $this->queryPath('/epp:epp/epp:response/epp:msgQ/@count');
        }
    }

    /**
     * Determine the type of poll message
     * TYPE_TRANSFER
     * TYPE_CREATE
     * TYPE_UPDATE
     * TYPE_DELETE
     */
    public function getMessageType() {
        if ($this->messageType) {
            return $this->messageType;
        } else {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_TRANSFER;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_CREATE;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:chkData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_CHECK;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_INFO;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:panData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_PAN;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:renData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_RENEW;
            }
            return self::TYPE_UNKNOWN;
        }
    }

    public function getDomainName() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:name');
    }

    public function getDomainTrStatus() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:trStatus');
    }

    public function getDomainRequestClientId() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:reID');
    }

    public function getDomainRequestDate() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:reDate');
    }

    public function getDomainExpirationDate() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:exDate');
    }

    public function getDomainActionDate() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:acDate');
    }

    public function getDomainActionClientId() {
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:acID');
    }

}