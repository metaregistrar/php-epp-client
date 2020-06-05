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

class verisignEppPollResponse extends eppPollResponse {
    const TYPE_POLL = 'poll';
    const OBJECT_DOMAIN = 'domain';
    const OBJECT_CONTACT = 'contact';
    const OBJECT_RGP = 'rgp-poll';
    const OBJECT_UNKNOWN = 'unknown';
    private $objectType = null;
    private $messageType = null;
    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }
    
    public function getObjectType(){
        if ($this->objectType){
            return $this->objectType;
        }else{
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/*');
            return $result->item(0)->prefix;
        }
    }

    /**
     * Determine the type of poll message
     * TYPE_TRANSFER
     * TYPE_CREATE
     * TYPE_UPDATE
     * TYPE_DELETE
     * @return string
     */
    public function getMessageType() {
        $this->objectType = $this->getObjectType();
        if ($this->messageType) {
            return $this->messageType;
        } else {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':trnData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_TRANSFER;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':creData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_CREATE;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':chkData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_CHECK;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':infData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_INFO;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':panData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_PAN;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':renData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_RENEW;
            }
            $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':pollData');
            if ((is_object($result)) && ($result->length>0)) {
                return self::TYPE_POLL;
            }
            return self::TYPE_UNKNOWN;
        }
    }

    /**
     * 获取对象ID，一般为联系人ID
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getId(){
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':id');
    }
    /**
     * 获取对象名称，一般为域名
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getName(){
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':name');
    }
    /**
     * 取状态值
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getStatus() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':status');
        if ($result->length>0) {
            $object = $result[0];
            /* @var $object \domElement */
            return $object->getAttribute('s');
        }
        return null;
    }
    /**
     * 取状态说明文本
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getStatusText() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':status');
    }
    /**
     * 获取转移状态
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getTransferStatus() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':trStatus');
    }
    /**
     * 获取RGP状态
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getRgpStatus(){
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':rgpStatus/@s');
    }
    /**
     * 获取请求时间
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getRequestDate() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        $path = '/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data';
        if ($this->hasElement([$path.'/'.$this->objectType.':reqDate'])){
            return $this->queryPath($path.'/'.$this->objectType.':reqDate');
        }elseif($this->hasElement([$path.'/'.$this->objectType.':reDate'])){
            return $this->queryPath($path.'/'.$this->objectType.':reDate');
        }
        return null;
    }
    /**
     * 获取到期时间，一般为转移的截止时间
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getExpirationDate() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':exDate');
    }
    /**
     * 取操作时间
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getActionDate() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':acDate');
    }
    /**
     * 获取RGP报告提交的截止时间
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getReportDueDate() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':reportDueDate');
    }
    /**
     * 取请求的客户端ID
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getRequestClientId() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':reID');
    }
    /**
     * 取操作的客户端ID
     * @return string|null
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function getActionClientId() {
        $this->objectType = $this->getObjectType();
        $this->messageType = $this->getMessageType();
        return $this->queryPath('/epp:epp/epp:response/epp:resData/'.$this->objectType.':'.$this->messageType.'Data/'.$this->objectType.':acID');
    }
}