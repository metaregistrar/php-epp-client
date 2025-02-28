<?php

namespace Metaregistrar\EPP;

/**
 * <extension>
 * <keysys:create xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
 * <keysys:contact>
 * <keysys:preverify>1</keysys:preverify>
 * <keysys:verified>0</keysys:verified>
 * <keysys:validated>1</keysys:validated>
 * </keysys:contact>
 * </keysys:create>
 * </extension>
 */

class rrpproxyEppCreateContactRequest extends eppCreateContactRequest {

    private int $validation = 1;
    private int $preverify = 0;
    private int $checkonly = 0;

    /**
     * Constructor for the class.
     *
     * @param eppContact $contact          The contact object of type eppContact.
     * @param int        $validation       The validation level. Defaults to 1.
     * @param int        $checkonly        Indicates if only a check should be performed. Defaults to 0.
     * @param int        $preverify        Indicates if preverification is required. Defaults to 0.
     * @param bool       $namespacesinroot Specifies if namespaces should be in the root. Defaults to true.
     * @param bool       $usecdata         Specifies whether to use CDATA sections. Defaults to true.
     *
     * @return void
     * @throws eppException If $contact is not of type eppContact or when conflicting options (checkonly and preverify) are set.
     */
    function __construct(eppContact $contact, int $validation = 1, int $checkonly = 0, int $preverify = 0, bool $namespacesinroot = true, bool $usecdata = true) {
        try {
            parent::__construct($contact, $namespacesinroot, $usecdata);
            if($checkonly && $preverify) {
                throw new eppException('checkonly and preverify cannot be set at the same time');
            }
            $this->setValidation($validation);
            $this->setCheckOnly($checkonly);
            $this->setPreverify($preverify);
            $this->addElements();
        } catch(eppException $e) {
            throw new eppException('contact must be of type eppContact on rrpproxyEppCreateContactRequest');
        }
        parent::addSessionId();
    }

    /**
     * Destructor method that cleans up resources or performs any final tasks when the object is deleted.
     *
     * @return void
     */
    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Adds elements to the XML structure based on the configured validation, preverify, or check-only settings.
     *
     * This method dynamically creates and appends XML nodes depending on the combination
     * of validation, preverify, and check-only values. If none of these values are set, no elements will be added.
     *
     * @return void
     */
    private function addElements() : void {
        // Skip element additions if nothing set
        if(!$this->getPreverify() && !$this->getCheckOnly() && !$this->getValidation()) {
            return;
        }

        $ext = $this->createElement('extension');
        $cdata = $this->createElement('keysys:create');
        $cdata->setAttribute('xmlns:keysys', 'http://www.key-systems.net/epp/keysys-1.0');
        $infdata = $this->createElement('keysys:contact');

        if($this->getValidation()) {
            $vel = $this->createElement('keysys:validation', $this->getValidation());
            $infdata->appendChild($vel);
        }

        if($this->getPreverify() && !$this->getCheckOnly()) {
            $vereq = $this->createElement('keysys:preverify', $this->getPreverify());
            $infdata->appendChild($vereq);
        }

        if($this->getCheckOnly()) {
            $verel = $this->createElement('keysys:checkonly', $this->getCheckOnly());
            $infdata->appendChild($verel);
        }

        $cdata->appendChild($infdata);
        $ext->appendChild($cdata);

        $this->contactobject = $ext;
        $this->getCommand()->appendChild($ext);
    }

    /**
     * Sets the validation value to the specified integer.
     *
     * @param int $validation The value to set for validation.
     *
     * @return void
     */
    public function setValidation(int $validation) : void {
        $this->validation = $validation;
    }

    /**
     * Retrieves the validation value.
     *
     * @return int The current validation value.
     */
    public function getValidation() : int {
        return $this->validation;
    }

    /**
     * Sets the check-only flag to the specified value.
     *
     * @param int $checkonly The value to set for the check-only flag.
     *
     * @return void
     */
    public function setCheckOnly(int $checkonly) : void {
        $this->checkonly = $checkonly;
    }

    /**
     * Retrieves the check-only value.
     *
     * @return int The check-only value.
     */
    public function getCheckOnly() : int {
        return $this->checkonly;
    }

    /**
     * Sets the preverify value.
     *
     * @param int $preverify The preverify value to set.
     *
     * @return void
     */
    public function setPreverify(int $preverify) : void {
        $this->preverify = $preverify;
    }

    /**
     * Retrieves the preverify value.
     *
     * @return int The preverify value.
     */
    public function getPreverify() : int {
        return $this->preverify;
    }
}