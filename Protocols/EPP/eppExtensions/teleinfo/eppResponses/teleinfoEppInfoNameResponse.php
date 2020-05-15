<?php
namespace Metaregistrar\EPP;

class teleinfoEppInfoNameResponse extends eppResponse {
    public function getInput(){
        foreach(['rnv','dnv'] as $item){
            $path = '/epp:epp/epp:response/epp:resData/nv:infData/nv:input/nv:'.$item;
            if($this->hasElement([$path])){
                $result['name'] = $this->queryPath($path.'/nv:name');
                break;
            }
        }
        $rnvCode = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:input/nv:dnv/nv:rnvCode');
        !empty($rnvCode) && $result['rnv_code'] = $rnvCode;
        $role = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:input/nv:rnv/@role');
        !empty($role) && $result['role'] = $role;
        $number = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:input/nv:rnv/nv:num');
        !empty($number) && $result['number'] = $number;
        $proofType = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:input/nv:rnv/nv:proofType');
        !empty($proofType) && $result['proof_type'] = $proofType;
        $this->getInputDocuments() && $result['documents'] = $this->getInputDocuments();
        $password = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:input/nv:authInfo/nv:pw');
        !empty($password) && $result['password'] = $password;
        return $result;
    }
    public function getSignedCode(){
        $result['type'] = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:signedCode/nv:code/@type');
        $result['status'] = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:signedCode/nv:status/@s');
        $result['password'] = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:signedCode/nv:authInfo/nv:pw');
        $result['signed_code'] = $this->queryPath('/epp:epp/epp:response/epp:resData/nv:infData/nv:signedCode/nv:encodedSignedCode');
        return $result;
    }
    public function getInputDocuments(){
        $result = [];
        $xpath = $this->xPath();
        $documents = $xpath->query('/epp:epp/epp:response/epp:resData/nv:infData/nv:input/nv:rnv/nv:document');
        if ($documents->length > 0){
            foreach($documents as $doc){
                unset($resultItem);
                foreach($doc->childNodes as $child){
                    if ($child instanceof \DOMElement){
                        switch($child->localName){
                            case 'fileType':
                                $resultItem['type'] = trim($child->nodeValue);
                                break;
                            case 'fileContent':
                                $resultItem['content'] = trim($child->nodeValue);
                                break;
                        }
                    }
                }
                $result[] = $resultItem;
            }
        }
        return $result;
    }
}

