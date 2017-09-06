<?php
namespace Metaregistrar\EPP;

class noridEppDomain extends eppDomain {

    private $extToken = null;
    private $extNotifyMobilePhone = null;
    private $extNotifyEmail = null;
    private $extDeleteFromDNS = null;
    private $extDeleteFromRegistry = null;
    private $extApplicantDatasetVersionNumber = '2.0';
    private $extApplicantDatasetAcceptName = null;
    private $extApplicantDatasetAcceptDate = null;

    public function __construct($domainname, $registrant = null, $contacts = null, $hosts = null, $period = 0, $authorisationCode = null, $extToken = null, $extNotifyMobilePhone = null, $extNotifyEmail = null, $extDeleteFromDNS = null, $extDeleteFromRegistry = null, $extApplicantDatasetVersionNumber = null, $extApplicantDatasetAcceptName = null, $extApplicantDatasetAcceptDate = null) {
        parent::__construct($domainname, $registrant, $contacts, $hosts, $period, $authorisationCode);

        // Set extension values
        $this->setExtToken($extToken);
        $this->setExtNotify($extNotifyMobilePhone, $extNotifyEmail);
        $this->setExtDeleteFromDNS($extDeleteFromDNS);
        $this->setExtDeleteFromRegistry($extDeleteFromRegistry);
        $this->setExtApplicantDataset($extApplicantDatasetVersionNumber, $extApplicantDatasetAcceptName, $extApplicantDatasetAcceptDate);
    }

    public function setExtToken($token) {
        if (!is_null($token)) {
            $this->extToken = $token;
        }
    }

    public function getExtToken() {
        return $this->extToken;
    }

    public function setExtNotify($mobilePhone, $email) {
        if (!is_null($mobilePhone)) {
            $this->extNotifyMobilePhone = $mobilePhone;
        }
        if (!is_null($email)) {
            $this->extNotifyEmail = $email;
        }
    }

    public function setExtNotifyMobilePhone($mobilePhone) {
        if (!is_null($mobilePhone)) {
            $this->extNotifyMobilePhone = $mobilePhone;
        }
    }

    public function setExtNotifyEmail($email) {
        if (!is_null($email)) {
            $this->extNotifyEmail = $email;
        }
    }

    public function getExtNotifyMobilePhone() {
        return $this->extNotifyMobilePhone;
    }

    public function getExtNotifyEmail() {
        return $this->extNotifyEmail;
    }

    public function setExtDeleteFromDNS($deleteFromDNS) {
        if (!is_null($deleteFromDNS)) {
            $this->extDeleteFromDNS = $deleteFromDNS;
        }
    }

    public function getExtDeleteFromDNS() {
        return $this->extDeleteFromDNS;
    }

    public function setExtDeleteFromRegistry($deleteFromRegistry) {
        if (!is_null($deleteFromRegistry)) {
            $this->extDeleteFromRegistry = $deleteFromRegistry;
        }
    }

    public function getExtDeleteFromRegistry() {
        return $this->extDeleteFromRegistry;
    }

    public function setExtApplicantDataset($versionNumber, $acceptName, $acceptDate) {
        if (!is_null($versionNumber)) {
            $this->extApplicantDatasetVersionNumber = $versionNumber;
        }
        if (!is_null($acceptName)) {
            $this->extApplicantDatasetAcceptName = $acceptName;
        }
        if (!is_null($acceptDate)) {
            $this->extApplicantDatasetAcceptDate = $acceptDate;
        }
    }

    public function getExtApplicantDataset() {
        return array(
            'versionNumber' => $this->extApplicantDatasetVersionNumber,
            'acceptName' => $this->extApplicantDatasetAcceptName,
            'acceptDate' => $this->extApplicantDatasetAcceptDate
        );
    }

    public function getExtApplicantDatasetVersionNumber() {
        return $this->extApplicantDatasetVersionNumber;
    }

    public function getExtApplicantDatasetAcceptName() {
        return $this->extApplicantDatasetAcceptName;
    }

    public function getExtApplicantDatasetAcceptDate() {
        return $this->extApplicantDatasetAcceptDate;
    }

}