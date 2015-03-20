<?php
namespace Metaregistrar\EPP;
/*
    <extension>
      <sidn-ext-epp:ext>
        <sidn-ext-epp:create>
          <sidn-ext-epp:contact>
            <sidn-ext-epp:legalForm>BV</sidn-ext-epp:legalForm>
            <sidn-ext-epp:legalFormRegNo>8764654.0</sidn-ext-epp:legalFormRegNo>
          </sidn-ext-epp:contact>
        </sidn-ext-epp:create>
      </sidn-ext-epp:ext>
    </extension>
 */
class sidnEppCreateContactRequest extends eppCreateContactRequest {


    function __construct($createinfo) {
        parent::__construct($createinfo);
        if ($createinfo instanceof eppContact) {
            $this->addSidnExtension($createinfo);
        }
        $this->addSessionId();
    }

    private function addSidnExtension(eppContact $contact) {
        $postal = $contact->getPostalInfo(0);

        if (strlen($postal->getOrganisationName())) {
            $legalform = 'ANDERS';
        } else {
            $legalform= 'PERSOON';
        }
        $sidnext = $this->createElement('sidn-ext-epp:ext');
        $create = $this->createElement('sidn-ext-epp:create');
        $contact = $this->createElement('sidn-ext-epp:contact');
        $contact->appendChild($this->createElement('sidn-ext-epp:legalForm', $legalform));
        #$contact->appendChild($this->createElement('sidn-ext-epp:legalFormRegNo','8764654.0'));
        $create->appendChild($contact);
        $sidnext->appendChild($create);
        $this->getExtension()->appendChild($sidnext);

    }
}