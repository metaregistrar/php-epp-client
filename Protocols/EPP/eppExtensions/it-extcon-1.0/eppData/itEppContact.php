<?php

namespace Metaregistrar\EPP;

/**
 * The Contact Info Object
 *
 * This will hold the complete contact info a registry can receive and give you
 *
 */

class itEppContact extends eppContact
{

    private $entityTypes = [
        1, // Italian and foreign natural persons
        2, // Companies/one man companie
        3, // Freelance workers/professionals
        4, // Non-profit organizations
        5, // Public organizations
        6, // Other subjects
        7, // Foreigners who match 2-6
    ];

    private $consentForPublishing;
    private $registrantEntityType;
    private $registrantNationalityCode;
    private $registrantRegCode;

    public function __construct($postalInfo = null, $email = null, $voice = null, $fax = null, $password = null, $status = null, $consentForPublishing = null, $entityType = null, $nationalityCode = null, $regCode = null)
    {
        parent::__construct($postalInfo, $email, $voice, $fax, $password, $status);

        $this->setConsentForPublishing($consentForPublishing);
        $this->setRegistrant($entityType, $nationalityCode, $regCode);
    }

    public function setConsentForPublishing($consent = false)
    {
        $this->consentForPublishing = $consent ? 1 : 0;
    }

    public function getConsentForPublishing()
    {
        return $this->consentForPublishing;
    }

    public function setRegistrantEntityType($entityType)
    {
        if (!empty($entityType)) {
            if (!in_array($entityType, $this->entityTypes)) {
                throw new eppException(sprintf('The entity type: \'%s\' is invalid', $entityType));
            }
            $this->registrantEntityType = $entityType;
        }
    }

    public function setRegistrantNationalityCode($nationalityCode)
    {
        if (!empty($nationalityCode)) {
            $this->registrantNationalityCode = $nationalityCode;
        }
    }

    public function setRegistrantRegCode($regCode)
    {
        if (!empty($regCode)) {
            $this->registrantRegCode = $regCode;
        }
    }

    public function setRegistrant($entityType, $nationalityCode, $regCode)
    {
        $this->setRegistrantEntityType($entityType);
        $this->setRegistrantNationalityCode($nationalityCode);
        $this->setRegistrantRegCode($regCode);
    }

    public function getRegistrant()
    {
        if (empty($this->registrantEntityType) || empty($this->registrantNationalityCode) || empty($this->registrantRegCode)) {
            return null;
        } else {
            return [
                'entityType' => $this->registrantEntityType,
                'nationalityCode' => $this->registrantNationalityCode,
                'regCode' => $this->registrantRegCode
            ];
        }
    }
}
