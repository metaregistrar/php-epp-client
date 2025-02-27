<?php
// +----------------------------------------------------------------------
// | 在我们年轻的城市里，没有不可能的事！
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://srs.micang.com All rights reserved.
// +----------------------------------------------------------------------
// | Author : Jansen <jansen.shi@qq.com>
// +----------------------------------------------------------------------
namespace Metaregistrar\EPP;
trait verisignEppExtension{
    /**
     * add verisign namestore extension
     * @param eppDomain $domain
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function addNamestore(?eppDomain $domain=null){
        if ($domain instanceof eppDomain){
            $tld = substr(strrchr($domain->getDomainname(), '.'), 1);
        }else{
            $tld = 'com';
        }
        $namestoreExt = $this->createElement('namestoreExt:namestoreExt');
        $namestoreExt->setAttribute('xmlns:namestoreExt', 'http://www.verisign-grs.com/epp/namestoreExt-1.1');
        $namestoreExt->appendChild($this->createElement('namestoreExt:subProduct', strtoupper($tld)));
        $this->getExtension()->appendChild($namestoreExt);
    }
    /**
     * add idn language extension
     * @param string $lang idn language tag value
     * @see https://www.verisign.com/assets/idn-valid-language-tags.pdf
     */
    public function addIdnLang(string $lang='ENG'){
        $idnLangExt = $this->createElement('idnLang:tag', $lang);
        $idnLangExt->setAttribute('xmlns:idnLang', 'http://www.verisign.com/epp/idnLang-1.0');
        $this->getExtension()->appendChild($idnLangExt);
    }
    /**
     * add verification code extendsion
     * @param string $rnvc real name verification code
     * @param string $dnvc domain name verification code
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function addVerificationCode(?string $rnvc=null, ?string $dnvc=null){
        //添加实名认证拓展
        $verifyExt = $this->createElement('verificationCode:encodedSignedCode');
        $verifyExt->setAttribute('xmlns:verificationCode', 'urn:ietf:params:xml:ns:verificationCode-1.0');
        if (!empty($rnvc)) {
            $verifyExt->appendChild($this->createElement('verificationCode:code', $rnvc));
        }
        if(!empty($dnvc)){
            $verifyExt->appendChild($this->createElement('verificationCode:code', $dnvc));
        }
        $this->getExtension()->appendChild($verifyExt);
    }

    /**
     * add verification code info extendsion
     * @return void
     * @throws \DOMException
     */
    public function addVerificationCodeInfo(){
        $verifyExt = $this->createElement('verificationCode:info');
        $verifyExt->setAttribute('xmlns:verificationCode', 'urn:ietf:params:xml:ns:verificationCode-1.0');
        $this->getExtension()->appendChild($verifyExt);
    }
}
