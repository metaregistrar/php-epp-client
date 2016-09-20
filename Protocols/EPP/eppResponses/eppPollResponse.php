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
    const TYPE_UPDATE = 'upd';
    const TYPE_DELETE = 'del';

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
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/@id');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * Return the date of the message
     * @return null|string
     */
    public function getMessageDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/epp:qDate');
        if (is_object($result) && ($result->length > 0)) {
            return trim($result->item(0)->nodeValue);
        } else {
            return null;
        }
    }

    /**
     * Return the poll message
     * @return null|string
     */
    public function getMessage() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/epp:msg');
        if (is_object($result) && ($result->length > 0)) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * return the number of messages that remain
     * @return int|string
     */
    public function getMessageCount() {
        if ($this->getResultCode() == eppResponse::RESULT_NO_MESSAGES) {
            return 0;
        } else {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/@count');
            return $result->item(0)->nodeValue;
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
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:name');
            if (is_object($result)) {
                return self::TYPE_TRANSFER;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:creData');
            if (is_object($result)) {
                return self::TYPE_CREATE;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:updData');
            if (is_object($result)) {
                return self::TYPE_UPDATE;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:delData');
            if (is_object($result)) {
                return self::TYPE_DELETE;
            }
            throw new eppException("Type of message cannot be determined on EPP poll message");
        }
    }

    public function getDomainName() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:name');
        return $result->item(0)->nodeValue;
    }

    public function getDomainTrStatus() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:'.substr($this->messageType,0,2).'Status');
        return $result->item(0)->nodeValue;
    }

    public function getDomainRequestClientId() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:reID');
        return $result->item(0)->nodeValue;
    }

    public function getDomainRequestDate() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:reDate');
        return $result->item(0)->nodeValue;
    }

    public function getDomainExpirationDate() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:exDate');
        return $result->item(0)->nodeValue;
    }

    public function getDomainActionDate() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:acDate');
        return $result->item(0)->nodeValue;
    }

    public function getDomainActionClientId() {
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:'.$this->messageType.'Data/domain:acID');
        return $result->item(0)->nodeValue;
    }

}