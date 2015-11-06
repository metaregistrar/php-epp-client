<?php
namespace Metaregistrar\EPP;

class eppCheckContactRequest extends eppRequest {
    function __construct($checkrequest) {
        parent::__construct();

        if ($checkrequest instanceof eppContactHandle) {
            $this->setContactHandles(array($checkrequest));
        } else {
            if (is_array($checkrequest)) {
                if ($checkrequest[0] instanceof eppContactHandle) {
                    $this->setContactHandles($checkrequest);
                }
            }
        }
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setContactHandles($contacthandles) {
        #
        # Domain check structure
        #
        $check = $this->createElement('check');
        $this->contactobject = $this->createElement('contact:check');
        foreach ($contacthandles as $contacthandle) {
            if ($contacthandle instanceof eppContactHandle) {
                $this->contactobject->appendChild($this->createElement('contact:id', $contacthandle->getContactHandle()));
            } else {
                $this->contactobject->appendChild($this->createElement('contact:id', $contacthandle));
            }
        }
        $check->appendChild($this->contactobject);
        $this->getCommand()->appendChild($check);
    }


}