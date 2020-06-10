<?php
namespace Metaregistrar\EPP;

class verisignEppDeleteContactRequest extends eppDeleteContactRequest {
    use verisignEppExtension;
    /**
     * verisignEppDeleteContactRequest constructor.
     *
     * @param eppContactHandle $contact
     * @throws eppException
     */
    public function __construct(eppContactHandle $contact) {
        parent::__construct($contact);
        //add namestore extension
        $this->addNamestore();
        $this->addSessionId();

    }
}
