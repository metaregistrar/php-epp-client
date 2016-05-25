<?php
namespace Metaregistrar\EPP;

class eppDeleteContactRequest extends eppContactRequest {

    function __construct(eppContactHandle $deleteinfo, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_DELETE);

        if ($deleteinfo instanceof eppContactHandle) {
            $this->setContactHandle($deleteinfo);
        } else {
            throw new eppException('parameter of eppDeleteRequest must be valid eppContactHandle object');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setContactHandle(eppContactHandle $contacthandle) {
        if (!strlen($contacthandle->getContactHandle())) {
            throw new eppException('eppDeleteRequest contacthandle object does not contain a valid contacthandle');
        }
        #
        # Object delete structure
        #
        $this->contactobject->appendChild($this->createElement('contact:id', $contacthandle->getContactHandle()));
    }



}