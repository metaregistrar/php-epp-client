<?php
namespace Metaregistrar\EPP;
/**
 * The EPP Contact Handle Object
 *
 * This will hold the complete contact info the provider can receive and give you
 *
 *
 *
 */

class eppContactHandle {
    const CONTACT_TYPE_REGISTRANT = 'reg';
    const CONTACT_TYPE_ADMIN = 'admin';
    const CONTACT_TYPE_TECH = 'tech';
    const CONTACT_TYPE_BILLING = 'billing';
    const CONTACT_TYPE_ONSITE = 'onsite'; //USED FOR .EU AND .BE ONLY
    /**
     * Registry handle of contact
     * @var string
     */
    private $contactHandle;
    /**
     * Type of contact: ADMIN, TECH, BILLING
     * @var string
     */
    private $contactType;
    /**
     * Authcode to retrieve contact information
     * @var string
     */
    private $password=null;

    /**
     *
     * @param string $contactHandle
     * @param string $contactType
     * @throws eppException
     */
    public function  __construct($contactHandle, $contactType = null) {
        $this->setContactHandle($contactHandle);
        if ($contactType) {
            $this->setContactType($contactType);
        }
        if (($contactType != null) && ($contactType != self::CONTACT_TYPE_ADMIN) && ($contactType != self::CONTACT_TYPE_REGISTRANT) && ($contactType != self::CONTACT_TYPE_BILLING) && ($contactType != self::CONTACT_TYPE_TECH) && ($contactType != self::CONTACT_TYPE_ONSITE)) {
            throw new eppException('Invalid contact type: ' . $contactType);
        }
    }

    /**
     * Gets the contact handle
     * @return string
     */
    public function getContactHandle() {
        return $this->contactHandle;
    }

    /**
     * Set the handle of the desired contact
     * @param string $contactHandle
     * @throws eppException
     */
    public function setContactHandle($contactHandle) {
        if (!strlen($contactHandle)) {
            throw new eppException('Contact handle specified is not valid: ' . $contactHandle);
        }
        $this->contactHandle = $contactHandle;
    }

    /**
     * Gets the contact handle
     * @return string
     */
    public function getContactType() {
        return $this->contactType;
    }

    /**
     * Sets the contact type
     * @param string $contactType
     * @return void
     */
    public function setContactType($contactType) {
        $this->contactType = $contactType;
    }

    /**
     * Sets the password
     *
     * **NOTE** This is not used by at the moment, but they do require it to be given
     * @param string $password
     * @return void
     */

    public function setPassword($password) {
        $this->password = htmlspecialchars($password, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

}


