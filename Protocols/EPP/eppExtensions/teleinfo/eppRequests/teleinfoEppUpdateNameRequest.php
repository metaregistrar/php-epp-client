<?php
namespace Metaregistrar\EPP;

class teleinfoEppUpdateNameRequest extends teleinfoEppNameRequest {
    function __construct(string $code, string $password) {
        $this->setNamespacesinroot(false);
        parent::__construct(eppRequest::TYPE_UPDATE);
        $this->nameobject->appendChild($this->createElement('nv:code', $code));
        $this->setUseCdata(true);
        $authinfo = $this->createElement('nv:authInfo');
        if ($this->useCdata()) {
            $pw = $authinfo->appendChild($this->createElement('nv:pw'));
            $pw->appendChild($this->createCDATASection($password));
        } else {
            $authinfo->appendChild($this->createElement('nv:pw', $password));
        }
        $change = $this->createElement('nv:chg');
        $change->appendChild($authinfo);
        $this->nameobject->appendChild($change);
        $this->addSessionId();
    }
}