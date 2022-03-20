<?php
namespace Metaregistrar\EPP;

class teleinfoEppCreateNameResponse extends eppResponse {
    
    public function getResult(){
        $result = [];
        $result['status'] = $this->getStatus();
        if ($result['status'] == 'nonCompliant'){
            $result['reason'] = $this->getReason();
        }else{
            $result['type'] = $this->getType();
            $result['create_date'] = $this->getCreateDate();
            $result['code'] = $this->getCode();
            if ($result['status'] == 'compliant'){
                $result['signed_code'] = $this->getEncodedSignedCode();
            }
        }
        return $result;
    }
    public function getType(){
        foreach(['success','pending'] as $item){
            $path = '/epp:epp/epp:response/epp:resData/nv:creData/nv:'.$item;
            if($this->hasElement([$path])){
                return $this->queryPath($path.'/nv:code/@type');
            }
        }
        return null;
    }
    public function getCode(){
        foreach(['success','pending'] as $item){
            $path = '/epp:epp/epp:response/epp:resData/nv:creData/nv:'.$item;
            if($this->hasElement([$path])){
                return $this->queryPath($path.'/nv:code');
            }
        }
        return null;
    }
    public function getStatus(){
        foreach(['success','failed','pending'] as $item){
            $path = '/epp:epp/epp:response/epp:resData/nv:creData/nv:'.$item;
            if($this->hasElement([$path])){
                return $this->queryPath($path.'/nv:status/@s');
            }
        }
        return null;
    }
    public function getCreateDate(){
        foreach(['success','pending'] as $item){
            $path = '/epp:epp/epp:response/epp:resData/nv:creData/nv:'.$item;
            if($this->hasElement([$path])){
                return $this->queryPath($path.'/nv:crDate');
            }
        }
        return null;
    }
    public function getEncodedSignedCode(){
        return $this->queryPath('/epp:epp/epp:response/epp:resData/nv:creData/nv:success/nv:encodedSignedCode');
    }
    public function getReason(){
        $path = '/epp:epp/epp:response/epp:resData/nv:creData/nv:failed';
        if($this->hasElement([$path])){
            return $this->queryPath($path.'/nv:msg');
        }
    }
}
