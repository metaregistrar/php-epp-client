<?php
namespace Metaregistrar\EPP;

class noridEppContact extends eppContact {
    
    // Contact types
    CONST NO_CONTACT_TYPE_ORGANIZATION = 'organization';
    CONST NO_CONTACT_TYPE_PERSON = 'person';
    CONST NO_CONTACT_TYPE_ROLE = 'role';

    // Identity types
    CONST NO_IDENTITY_TYPE_LOCAL = 'localIdentity';
    CONST NO_IDENTITY_TYPE_PERSON = 'anonymousPersonIdentifier';
    CONST NO_IDENTITY_TYPE_NATIONAL = 'nationalIdentityNumber';
    CONST NO_IDENTITY_TYPE_ORGANIZATION = 'organizationNumber';

    private $extType = null;
    private $extIdentityType = null;
    private $extIdentity = null;
    private $extMobilePhone = null;
    private $extEmails = null;
    private $extOrganizations = null;
    private $extRoleContacts = null;

    function __construct($postalInfo = null, $email = null, $voice = null, $fax = null, $password = null, $status = null, $extType = null, $extIdentityType = null, $extIdentity = null, $extMobilePhone = null, $extEmails = null, $extOrganizations = null, $extRoleContacts = null) {
        parent::__construct($postalInfo, $email, $voice, $fax, $password, $status);
        $this->setExtType($extType);
        $this->setExtIdentity($extIdentityType, $extIdentity);
        $this->setExtMobilePhone($extMobilePhone);
        $this->setExtEmails($extEmails);
        $this->setExtOrganizations($extOrganizations);
        $this->setExtRoleContacts($extRoleContacts);
    }

    public function setExtType($extType) {
        if ($extType !== self::NO_CONTACT_TYPE_ORGANIZATION || $extType !== self::NO_CONTACT_TYPE_PERSON || $extType !== self::NO_CONTACT_TYPE_ROLE) {
            throw new eppException('Invalid contact type specified');
        }

        $this->extType = $extType;
    }

    public function getExtType() {
        return $this->extType;
    }

    public function setExtMobilePhone($phone) {
        $this->extMobilePhone = $this->validatePhoneNumber($phone);
    }

    public function getExtMobilePhone() {
        return $this->extMobilePhone;
    }

    public function setExtIdentity($type, $identity) {
        if (!is_null($type) && !is_null($identity)) {
            if (is_null($this->extType)) {
                throw new eppException('You must set the contact type before setting the identity');
            } else {
                if ($type === self::NO_IDENTITY_TYPE_NATIONAL) {
                    throw new eppException('The nationalIdentityNumber identity type is not yet supported by Norid');
                }
                
                if ($this->extType === self::NO_CONTACT_TYPE_ORGANIZATION) {
                    if ($type !== self::NO_IDENTITY_TYPE_LOCAL && $type !== self::NO_IDENTITY_TYPE_ORGANIZATION) {
                        throw new eppException('Invalid identity type for contact of type organization');
                    }
                } elseif ($this->extType === self::NO_CONTACT_TYPE_PERSON) {
                    if ($type !== self::NO_IDENTITY_TYPE_PERSON) {
                        throw new eppException('Invalid identity type for contact of type person');
                    }
                } elseif ($this->extType === self::NO_CONTACT_TYPE_ROLE) {
                    throw new eppException('Identity is not supported for contact of type role');
                }
            }

            $this->extIdentityType = $type;
            $this->extIdentity = $identity;
        }
    }

    public function getExtIdentityType() {
        return $this->extIdentityType;
    }

    public function getExtIdentity() {
        return $this->extIdentity;
    }

    public function setExtEmails($emails) {
        if (!is_null($emails)) {
            if (is_array($emails)) {
                $this->extEmails = array();
                foreach ($emails as $email) {
                    // Use addExtEmail to validate the emails
                    $this->addExtEmail($email);
                }
                $this->extEmails = $emails;
            } elseif (is_string($emails)) {
                $this->extEmails = array();
                // Use addExtEmail to validate the emails
                $this->addExtEmail($emails);
            } else {
                throw new eppException('setExtEmails must be called with either an array of emails or a single email address');
            }
        }
    }

    public function addExtEmail($email) {
        if ($this->extEmails === null) {
            $this->extEmails = array();
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $this->extEmails[] = $email;
        } else {
            throw new eppException('Invalid email address');
        }
    }

    public function removeExtEmail($email) {
        if (is_array($this->extEmails)) {
            if (($key = array_search($this->extEmails, $email)) !== false) {
                unset($this->extEmails[$key]);
            }
        }
    }

    public function getExtEmails() {
        return $this->extEmails;
    }

    public function setExtOrganizations($organizations) {
        if (!is_null($organizations)) {
            if (is_array($organizations)) {
                $this->extOrganizations = $organizations;
            } elseif (is_string($organizations)) {
                $this->extOrganizations = array($organizations);
            } else {
                throw new eppException('setExtOrganizations must be called with either an array of organization IDs or a single organization ID');
            }
        }
    }

    public function addExtOrganization($organization) {
        if ($this->extOrganizations === null) {
            $this->extOrganizations = array();
        }
        $this->extOrganizations[] = $organization;
    }

    public function removeExtOrganization($organization) {
        if (is_array($this->extOrganizations)) {
            if (($key = array_search($this->extOrganizations, $organization)) !== false) {
                unset($this->extOrganizations[$key]);
            }
        }
    }

    public function getExtOrganizations() {
        return $this->extOrganizations;
    }

    public function setExtRoleContacts($contacts) {
        if (!is_null($contacts)) {
            if ($this->extType === self::NO_CONTACT_TYPE_ROLE) {
                if (is_array($contacts)) {
                    $this->extRoleContacts = $contacts;
                } elseif (is_string($contacts)) {
                    $this->extRoleContacts = array($contacts);
                } else {
                    throw new eppException('setExtRoleContacts must be called with either an array of contact IDs or a single contact ID');
                }
            } else {
                throw new eppException('You can only add role contacts to a contact with an extType of NO_CONTACT_TYPE_ROLE');
            }
        }
    }

    public function addExtRoleContact($contact) {
        if ($this->extType === self::NO_CONTACT_TYPE_ROLE) {
            if ($this->extRoleContacts === null) {
                $this->extRoleContacts = array();
            }
            $this->extRoleContacts[] = $contact;
        } else {
            throw new eppException('You can only add role contacts to a contact with an extType of NO_CONTACT_TYPE_ROLE');
        }
    }

    public function removeExtRoleContact($contact) {
        if (is_array($this->extRoleContacts)) {
            if (($key = array_search($this->extRoleContacts, $contact)) !== false) {
                unset($this->extRoleContacts[$key]);
            }
        }
    }

    public function getExtRoleContacts() {
        return $this->extRoleContacts;
    }
    
}