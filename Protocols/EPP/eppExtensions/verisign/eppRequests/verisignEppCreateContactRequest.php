<?php
namespace Metaregistrar\EPP;

class verisignEppCreateContactRequest extends eppCreateContactRequest {
    use verisignEppExtension;
    /**
     * verisignEppCreateContactRequest constructor.
     *
     * @param eppContact $contact
     * @throws eppException
     */
    public function __construct(eppContact $contact) {
        parent::__construct($contact);
        //add namestore extension
        $this->addNamestore();
        $this->addSessionId();
    }
}
