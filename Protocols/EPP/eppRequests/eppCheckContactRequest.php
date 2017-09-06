<?php
namespace Metaregistrar\EPP;

class eppCheckContactRequest extends eppContactRequest {
    function __construct($checkrequest, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_CHECK);

        if ($checkrequest instanceof eppContactHandle) {
            $this->setContactHandles(array($checkrequest));
        } else {
            if (is_array($checkrequest)) {
                if ($checkrequest[0] instanceof eppContactHandle) {
                    $this->setContactHandles($checkrequest);
                }
            }
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }
    
    public function setContactHandles($contacthandles) {
        #
        # Domain check structure
        #
        foreach ($contacthandles as $contacthandle) {
            if ($contacthandle instanceof eppContactHandle) {
                $this->contactobject->appendChild($this->createElement('contact:id', $contacthandle->getContactHandle()));
            } else {
                $this->contactobject->appendChild($this->createElement('contact:id', $contacthandle));
            }
        }
    }


}