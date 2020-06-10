<?php
namespace Metaregistrar\EPP;

class verisignEppUpdateContactRequest extends eppUpdateContactRequest {
    use verisignEppExtension;
    /**
     * verisignEppUpdateContactRequest constructor.
     *
     * @param      $contactId
     * @param null $add
     * @param null $remove
     * @param null $update
     * @throws eppException
     */
    public function __construct($contactId, $add=null, $remove=null, $update=null) {
        parent::__construct($contactId, $add, $remove, $update);
        //add namestore extension
        $this->addNamestore();
        $this->addSessionId();

    }
}
