<?php
namespace Metaregistrar\EPP;

use DOMDocument;
use DOMElement;
use DOMException;

class sknicEppContactPostalInfo extends eppContactPostalInfo
{
    private $legalForm;
    private $corpIdent;
    private $birthDate;

    private $allowedLegalForms = [
        'PERS',
        'CORP',
    ];

    /**
     * @throws eppException
     */
    public function __construct($name = null, $city = null, $countrycode = null, $organisationName = null, $street = null, $province = null, $zipcode = null, $type = eppContact::TYPE_AUTO, $legalForm = null, $corpIdentOrBirthDate = null) {

        parent::__construct($name, $city, $countrycode, $organisationName, $street, $province, $zipcode, $type);

        if (!in_array($legalForm, $this->allowedLegalForms, true)) {
            throw new eppException('Invalid legal form. Allowed values are: ' . implode(', ', $this->allowedLegalForms));
        }

        $this->setLegalForm($legalForm);
        if ($legalForm === 'PERS') {
            $this->setBirthDate($corpIdentOrBirthDate);
        } else {
            $this->setCorpIdent($corpIdentOrBirthDate);
        }
    }

    public function setLegalForm(string $legalForm): self
    {
        $this->legalForm = $legalForm;
        return $this;
    }

    public function setCorpIdent(string $corpIdent): self
    {
        $this->corpIdent = htmlspecialchars($corpIdent, ENT_COMPAT, "UTF-8");
        return $this;
    }

    public function setBirthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @throws DOMException
     */
    public function generateXML(DOMDocument $xml): DOMElement
    {
        $extension = $xml->createElement('extension');
        $create = $xml->createElement('skContactIdent:create');
        $create->setAttribute('xmlns:skContactIdent', 'http://www.sk-nic.sk/xml/epp/sk-contact-ident-0.2');

        // Legal form (PERS or CORP)
        $legalFormElement = $xml->createElement('skContactIdent:legalForm', $this->legalForm);
        $create->appendChild($legalFormElement);

        // If CORP, add identification number
        if ($this->legalForm === 'CORP' && !empty($this->corpIdent)) {
            $identValue = $xml->createElement('skContactIdent:identValue');
            $corpIdentElement = $xml->createElement('skContactIdent:corpIdent', $this->corpIdent);
            $identValue->appendChild($corpIdentElement);
            $create->appendChild($identValue);
        }

        // If PERS, optionally add birthdate
        if ($this->legalForm === 'PERS' && !empty($this->birthDate)) {
            $identValue = $xml->createElement('skContactIdent:identValue');
            $birthDateElement = $xml->createElement('skContactIdent:persIdent', $this->birthDate);
            $identValue->appendChild($birthDateElement);
            $create->appendChild($identValue);
        }

        $extension->appendChild($create);
        return $extension;
    }
}
