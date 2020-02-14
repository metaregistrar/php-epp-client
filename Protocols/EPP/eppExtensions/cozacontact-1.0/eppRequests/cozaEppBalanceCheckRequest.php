<?php
namespace Metaregistrar\EPP;
/*
<epp:epp xmlns:epp="urn:ietf:params:xml:ns:epp-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:cozacontact="http://co.za/epp/extensions/cozacontact-1-0">
    <epp:command>
        <epp:info>
            <contact:info>
                <contact:id>RegistrarID</contact:id>
            </contact:info>
        </epp:info>
        <epp:extension>
            <cozacontact:info>
                <cozacontact:balance>true</cozacontact:balance>
            </cozacontact:info>
        </epp:extension>
    </epp:command>
</epp:epp>


*/
class cozaEppBalanceCheckRequest extends eppInfoContactRequest {
    function __construct($inforequest) {
        parent::__construct($inforequest);
        $this->addCozaExtension();
        $this->addSessionId();
    }


    public function addCozaExtension() {
        $this->addExtension('xmlns:cozacontact', 'http://co.za/epp/extensions/cozacontact-1-0');
        $info = $this->createElement('cozacontact:info');
        $infocontact = $this->createElement('cozacontact:balance','true');
        $info->appendChild($infocontact);
        $this->getExtension()->appendChild($info);
    }

}