<?php
namespace Metaregistrar\EPP;
/*
<epp:epp xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:epp="urn:ietf:params:xml:ns:epp-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:cozacontact="http://co.za/epp/extensions/cozacontact-1-0">
  <epp:command>
    <epp:update>
        <contact:update>
            <contact:id>ContactID</contact:id>
        </contact:update>
    </epp:update>
    <epp:extension>
        <cozacontact:update xsi:schemaLocation="http://co.za/epp/extensions/cozacontact-1-0 coza-contact-1.0.xsd" cancelPendingAction="PendingUpdate"/>
    </epp:extension>
  </epp:command>
</epp:epp>

*/
class cozaEppCancelPendingActionRequest extends eppUpdateContactRequest {
    function __construct($contact,$action) {
        parent::__construct($contact);
        $this->setAction($action);
        $this->addSessionId();
        return $this;
    }


    public function setAction($action) {
        $this->addExtension('xmlns:cozacontact', 'http://co.za/epp/extensions/cozacontact-1-0');
        $info = $this->createElement('cozacontact:update');
        //$info->setAttribute('xsi:schemaLocation',"http://co.za/epp/extensions/cozacontact-1-0 coza-contact-1.0.xsd");
        $info->setAttribute('cancelPendingAction',$action);
        $this->getExtension()->appendChild($info);
    }

}