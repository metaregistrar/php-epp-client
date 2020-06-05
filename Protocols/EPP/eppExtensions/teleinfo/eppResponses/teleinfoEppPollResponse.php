<?php
namespace Metaregistrar\EPP;

class teleinfoEppPollResponse extends eppPollResponse {
    public function    __construct() {
        parent::__construct();
    }
    public function getNVResult(string $type='req'){
        if (!in_array($type, ['req','ack'])){
            throw new eppException('Poll action type '.$type.' is invalid, only req or ack is allowed.');
        }
        $queueCount = $this->getMessageCount();
        if ($queueCount == 0)   return null;
        $result['queue']['count'] = $queueCount;
        if ($type == 'req'){
            $result['queue']['id'] = $this->getMessageId();
            $result['nv']['type'] = $this->getNVType();
            $result['nv']['code'] = $this->getNVCode();
            $result['nv']['status'] = $this->getNVStatus();
            $result['nv']['message'] = $this->getNVMessage();
            $result['nv']['date'] = $this->getNVDate();
        }
        return $result;
    }
    public function getNVType(){
        return $this->queryPath('/epp:epp/epp:response/epp:resData/nv:panData/nv:code/@type');
    }
    public function getNVCode(){
        return $this->queryPath('/epp:epp/epp:response/epp:resData/nv:panData/nv:code');
    }
    public function getNVStatus(){
        return $this->queryPath('/epp:epp/epp:response/epp:resData/nv:panData/nv:paStatus/@s');
    }
    public function getNVMessage(){
        return $this->queryPath('/epp:epp/epp:response/epp:resData/nv:panData/nv:msg');
    }
    public function getNVDate(){
        return $this->queryPath('/epp:epp/epp:response/epp:resData/nv:panData/nv:paDate');
    }
}