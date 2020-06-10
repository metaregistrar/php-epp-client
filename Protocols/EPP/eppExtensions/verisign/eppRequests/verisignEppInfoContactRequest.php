<?php
namespace Metaregistrar\EPP;

class verisignEppInfoContactRequest extends eppInfoContactRequest {
    use verisignEppExtension;
    /**
     * verisignEppInfoContactRequest constructor.
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
