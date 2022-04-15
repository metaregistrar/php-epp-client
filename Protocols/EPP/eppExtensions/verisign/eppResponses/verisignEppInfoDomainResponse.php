<?php
namespace Metaregistrar\EPP;

class verisignEppInfoDomainResponse extends eppInfoDomainResponse {
    public function getDomainRgps(){
        $rgps = null;
        if ($this->findNamespace('rgp')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/rgp:infData/*');
            foreach ($result as $item){
                $rgps[$item->getAttribute('s')] = str_replace('endDate=', '', $item->nodeValue);
            }
        }
        return $rgps;
    }
    public function getVerificationCodes(){
        $verifyCodes = null;
        if ($this->findNamespace('verificationCode')){
            $xpath = $this->xPath();
            $verifyCodes['status'] = $this->queryPath('/epp:epp/epp:response/epp:extension/verificationCode:infData/verificationCode:status');
            $profiles = $xpath->query('/epp:epp/epp:response/epp:extension/verificationCode:infData/verificationCode:profile');
            $verifyCodes['profile'] = null;
            if ($profiles->length > 0){
                foreach ($profiles as $index => $profile){
                    $verifyCodes['profile'][$index]['name'] = $profile->getAttribute('name');
                    foreach ($profile->childNodes as $profileChildNode){
                        if ($profileChildNode->nodeName == 'verificationCode:status'){
                            $verifyCodes['profile'][$index]['status'] = $profileChildNode->nodeValue;
                        }elseif ($profileChildNode->nodeName == 'verificationCode:set'){
                            foreach ($profileChildNode->childNodes as $profileSetItem){
                                $verifyCodes['profile'][$index]['set'][] = [
                                    'type'  => $profileSetItem->getAttribute('type'),
                                    'date'  => $profileSetItem->getAttribute('date'),
                                    'value' => $profileSetItem->nodeValue
                                ];
                            }
                        }elseif ($profileChildNode->nodeName == 'verificationCode:missing'){
                            foreach ($profileChildNode->childNodes as $profileMissingItem){
                                $verifyCodes['profile'][$index]['missing'][] = [
                                    'type'  => $profileMissingItem->getAttribute('type'),
                                    'date'  => $profileMissingItem->getAttribute('due')
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $verifyCodes;
    }
}
