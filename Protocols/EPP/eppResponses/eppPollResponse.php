<?php

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

class eppPollResponse extends eppResponse
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }


    public function getMessageId()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/@id');
        if (is_object($result) && ($result->length > 0))
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }

    public function getMessageDate()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/epp:qDate');
        if (is_object($result) && ($result->length > 0))
        {
          return trim($result->item(0)->nodeValue);
        }
        else
        {
          return null;
        }
    }
    
    public function getMessage()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/epp:msg');
        if (is_object($result) && ($result->length > 0))
        {
            return $result->item(0)->nodeValue;
        }
        else
        {
            return null;
        }
    }

    public function getMessageCount()
    {
        if ($this->getResultCode() == eppResponse::RESULT_NO_MESSAGES)
        {
            return 0;
        }
        else
        {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:msgQ/@count' );
            return $result->item(0)->nodeValue;
        }
    }


    public function getDomainName()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:name');
       return $result->item(0)->nodeValue;
    }

    public function getDomainTrStatus()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:trStatus');
       return $result->item(0)->nodeValue;
    }

    public function getDomainRequestClientId()
    {
       $xpath = $this->xPath($this);
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reID');
       return $result->item(0)->nodeValue;
    }

    public function getDomainRequestDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:reDate');
       return $result->item(0)->nodeValue;
    }

    public function getDomainExpirationDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:exDate');
       return $result->item(0)->nodeValue;
    }

    public function getDomainActionDate()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acDate');
       return $result->item(0)->nodeValue;
    }

    public function getDomainActionClientId()
    {
       $xpath = $this->xPath();
       $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:trnData/domain:acID');
       return $result->item(0)->nodeValue;
    }
    
}