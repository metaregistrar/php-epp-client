<?php
namespace Metaregistrar\EPP;

class teleinfoEppCreateNameRequest extends teleinfoEppNameRequest {
    /**
     * teleinfoEppCreateDomainRequest constructor.
     *
     * @param eppChinaName|eppRealName $name
     */
    public function __construct($name) {
        $this->setNamespacesinroot(false);
        $this->setForcehostattr(false);
        parent::__construct(eppRequest::TYPE_CREATE);
        $this->setUseCdata(true);
        $this->setName($name);
        $this->addSessionId();
    }
    /**
     *
     * @param eppChinaName|eppRealName $nameObject
     * @throws eppException
     */
    public function setName($nameObject) {
        if (!($nameObject instanceof eppChinaName) && !($nameObject instanceof eppRealName)){
            throw new eppException('No valid name object in create name request,only eppChinaName or eppRealName allow');
        }
        if (!strlen($nameObject->getName())) {
            throw new eppException('No valid name in create name request');
        }
        if ($nameObject instanceof eppChinaName){
            $dnv = $this->createElement('nv:dnv');
            $dnv->appendChild($this->createElement('nv:name', $nameObject->getName()));
            if (!empty($nameObject->getRNVCode())){
                $dnv->appendChild($this->createElement('nv:rnvCode', $nameObject->getRNVCode()));
            }
            $this->nameobject->appendChild($dnv);
        }
        if ($nameObject instanceof eppRealName){
            $rnv = $this->createElement('nv:rnv');
            $rnv->setAttribute('role', $nameObject->getRole());
            $rnv->appendChild($this->createElement('nv:name', $nameObject->getName()));
            $rnv->appendChild($this->createElement('nv:num', $nameObject->getNumber()));
            $rnv->appendChild($this->createElement('nv:proofType', $nameObject->getProof()));
            if (count($nameObject->getDocuments())){
                foreach($nameObject->getDocuments() as $doc){
                    $document = $this->createElement('nv:document');
                    $document->appendChild($this->createElement('nv:fileType', $doc['type']));
                    $document->appendChild($this->createElement('nv:fileContent', $doc['content']));
                    $rnv->appendChild($document);
                    unset($document);
                }
            }
            $this->nameobject->appendChild($rnv);
        }
        if (strlen($nameObject->getAuthorisationCode())) {
            $authinfo = $this->createElement('nv:authInfo');
            if ($this->useCdata()) {
                $pw = $authinfo->appendChild($this->createElement('nv:pw'));
                $pw->appendChild($this->createCDATASection($nameObject->getAuthorisationCode()));
            } else {
                $authinfo->appendChild($this->createElement('nv:pw', $nameObject->getAuthorisationCode()));
            }

            $this->nameobject->appendChild($authinfo);
        }
    }
}
