<?php
namespace Metaregistrar\EPP;

/*
 * This object contains all the logic to create an EPP contact:info command
 */

class eppInfoContactRequest extends eppContactRequest {

    function __construct($inforequest, $namespacesinroot = true, $usecdata = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_INFO);
        $this->setUseCdata($usecdata);
        if ($inforequest instanceof eppContactHandle) {
            $this->setContactHandle($inforequest);
        } else {
            throw new eppException('parameter of infocontactrequest needs to be eppContactHandle object');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    public function setContactHandle(eppContactHandle $contacthandle) {
        #
        # Domain check structure
        #
        $this->contactobject->appendChild($this->createElement('contact:id', $contacthandle->getContactHandle()));
        if (!is_null($contacthandle->getPassword()))
        {
            $authinfo = $this->createElement('contact:authInfo');
            if ($this->useCdata()) {
                $authinfo->appendChild($this->createElement('contact:pw', $this->createCDATASection($contacthandle->getPassword())));
            } else {
                $authinfo->appendChild($this->createElement('contact:pw', $contacthandle->getPassword()));
            }
            $this->contactobject->appendChild($authinfo);
        }
    }

}
