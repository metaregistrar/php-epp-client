<?php
namespace Metaregistrar\EPP;

class teleinfoEppInfoNameRequest extends teleinfoEppNameRequest {

    function __construct(string $type, string $code, ?string $password=null) {
        $this->setNamespacesinroot(false);
        parent::__construct(eppRequest::TYPE_INFO);
        if ($type!='signedCode' && $type!='input'){
            throw new eppException('Info type '.$type.'is invalid, only signedCode or input allowed');
        }
        $this->nameobject->setAttribute('type', $type);
        $this->nameobject->appendChild($this->createElement('nv:code', $code));
        if (!empty($password)){
            $this->setUseCdata(true);
            $authinfo = $this->createElement('nv:authInfo');
            if ($this->useCdata()) {
                $pw = $authinfo->appendChild($this->createElement('nv:pw'));
                $pw->appendChild($this->createCDATASection($password));
            } else {
                $authinfo->appendChild($this->createElement('nv:pw', $password));
            }
            $this->nameobject->appendChild($authinfo);
        }
        $this->addSessionId();
    }
}