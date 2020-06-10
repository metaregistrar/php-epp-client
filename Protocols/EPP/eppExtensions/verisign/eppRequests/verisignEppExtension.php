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
    public function addNamestore(eppDomain $domain=null){
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
     * add verification code extendsion
     * @param string $rnvc real name verification code
     * @param string $dnvc domain name verification code
     * @author:Jansen <jansen.shi@qq.com>
     */
    public function addVerificationCode(string $rnvc, string $dnvc=null){
        //添加实名认证拓展
        $verifyExt = $this->createElement('verificationCode:encodedSignedCode');
        $verifyExt->setAttribute('xmlns:verificationCode', 'urn:ietf:params:xml:ns:verificationCode-1.0');
        $verifyExt->appendChild($this->createElement('verificationCode:code', $rnvc));
        if(!empty($dnvc)){
            $verifyExt->appendChild($this->createElement('verificationCode:code', $dnvc));
        }
        $this->getExtension()->appendChild($verifyExt);
    }
}