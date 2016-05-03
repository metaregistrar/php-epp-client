<?php
namespace Metaregistrar\EPP;

/*
 * This object contains all the logic to create an EPP contact:info command
 */

class eppInfoContactRequest extends eppRequest {

    function __construct($inforequest) {
        parent::__construct();

        if ($inforequest instanceof eppContactHandle) {
            $this->setContactHandle($inforequest);
        } else {
            throw new eppException('parameter of infocontactrequest needs to be eeppContact object');
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
        $info = $this->createElement('info');
        $this->contactobject = $this->createElement('contact:info');
        $this->contactobject->appendChild($this->createElement('contact:id', $contacthandle->getContactHandle()));
        if (!is_null($contacthandle->getPassword()))
        {
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw', $contacthandle->getPassword()));
            $this->contactobject->appendChild($authinfo);
        }
        $info->appendChild($this->contactobject);
        $this->getCommand()->appendChild($info);
    }

}
