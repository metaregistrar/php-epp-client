<?php

namespace Metaregistrar\EPP;

/**
 * <keysys:resData xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
 * <keysys:contactInfData>
 * <keysys:validated>1</keysys:validated>
 * <keysys:verification-requested>1</keysys:verification-requested>
 * <keysys:verified>0</keysys:verified>
 * </keysys:contactInfData>
 * </keysys:resData>
 */

class rrpproxyEppCreateContactRequest extends eppCreateContactRequest {

    /**
     * @var int
     */
    private int $validated = 0;
    private int $verification_requested = 0;
    private int $verified = 0;

    function __construct(eppContact $contact, int $validated = 0, int $verified = 0, int $verification_requested = 0, bool $namespacesinroot = true, bool $usecdata = true) {
        try {
            parent::__construct($contact, $namespacesinroot, $usecdata);
            $this->setValidated($validated);
            $this->setVerified($verified);
            $this->setVerificationRequested($verification_requested);
            $this->addElements();
        } catch(eppException $e) {
            throw new eppException('contact must be of type eppContact on rrpproxyEppCreateContactRequest');
        }
        parent::addSessionId();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    private function addElements() : void {
        $ext = $this->createElement('extension');
        $resdata = $this->createElement('keysys:resData');
        $resdata->setAttribute('xmlns:keysys', 'http://www.key-systems.net/epp/keysys-1.0');
        $infdata = $this->createElement('keysys:contactInfData');

        $vel = $this->createElement('keysys:validated', $this->getValidated());
        $vereq = $this->createElement('keysys:verificationRequested', $this->getVerificationRequested());
        $verel = $this->createElement('keysys:verified', $this->getVerified());

        $infdata->appendChild($vel);
        $infdata->appendChild($vereq);
        $infdata->appendChild($verel);

        $resdata->appendChild($infdata);
        $ext->appendChild($resdata);

        $this->contactobject = $ext;
        $this->getCommand()->appendChild($ext);
        var_dump($this->getCommand());
    }

    public function setValidated(int $validated) : void {
        $this->validated = $validated;
    }

    public function getValidated() : int {
        return $this->validated;
    }

    public function setVerified(int $verified) : void {
        $this->verified = $verified;
    }

    public function getVerified() : int {
        return $this->verified;
    }

    public function setVerificationRequested(int $verification_requested) : void {
        $this->verification_requested = $verification_requested;
    }

    public function getVerificationRequested() : int {
        return $this->verification_requested;
    }
}