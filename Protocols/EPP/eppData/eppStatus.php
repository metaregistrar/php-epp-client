<?php
namespace Metaregistrar\EPP;

class eppStatus {
    #
    # These status values cannot be set, only viewed
    #
    const STATUS_OK = 'ok';
    const STATUS_SERVER_DELETE_PROHIBITED = 'serverDeleteProhibited';
    const STATUS_SERVER_UPDATE_PROHIBITED = 'serverUpdateProhibited';
    const STATUS_SERVER_RENEW_PROHIBITED = 'serverRenewProhibited';
    const STATUS_SERVER_TRANSFER_PROHIBITED = 'serverTransferProhibited';
    const STATUS_SERVER_HOLD = 'serverHold';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING_CREATE = 'pendingCreate';
    const STATUS_PENDING_DELETE = 'pendingDelete';
    const STATUS_PENDING_TRANSFER = 'pendingTransfer';
    const STATUS_PENDING_UPDATE = 'pendingUpdate';
    const STATUS_PENDING_RENEW = 'pendingRenew';

    #
    # These status values can be set
    #
    const STATUS_CLIENT_DELETE_PROHIBITED = 'clientDeleteProhibited';
    const STATUS_CLIENT_UPDATE_PROHIBITED = 'clientUpdateProhibited';
    const STATUS_CLIENT_RENEW_PROHIBITED = 'clientRenewProhibited';
    const STATUS_CLIENT_TRANSFER_PROHIBITED = 'clientTransferProhibited';
    const STATUS_CLIENT_HOLD = 'clientHold';



    /**
     * Holds the status name
     * @var string 
     */
    private $statusname;

    /**
     * Holds the language from the status
     * @var string
     */
    private $language;

    /**
     * Holds the status message 
    * @var string
     */
    private $message;

    /**
     *
     * @param string $statusname
     * @param ?string $language
     * @param ?string $message
     */
    public function  __construct($statusname, $language = null, $message = null) {
        $this->setStatusname($statusname);
        if ($language) {
            $this->setLanguage($language);
        }
        if ($message) {
            $this->setMessage($message);
        }
    }

    // getters
    public function getStatusname() {
        return $this->statusname;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getMessage() {
        return $this->message;
    }


    // setters
    public function setStatusname($statusname) {
        if (strlen($statusname) > 0) {
            $this->statusname = $statusname;
        } else {
            throw new eppException("Statusname cannot be empty on eppStatus object");
        }
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

}
