<?php
namespace Metaregistrar\EPP;

/** 
 * Implementation of https://github.com/nic-at/epp-verification-extension
 */
class atEppVerificationReport
{

     /**
     * verification result: success or  failure
     * @var string
     */
    private $result;

    /**
     * date of validation in format yyyy-mm-ddThh:mm:ssZ
     * @var string
     */
    private $verificationDate;

    /**
     * description of the validation method
     * @var string
     */    
    private $method;


    /**
     * internal identifiier to identifiy validation
     * @var string
     */    

    private $reference;

    /**
     * name of entity which did the valdiation
     * @var string
     */    
    private $agent;

    /**
     * timestamp when validation report was uploaded to registry format yyyy-mm-ddThh:mm:ssZ
     * @var string
     */
    private $receivedDate;

    /**
     * clID of transaction which uploaded the validation report 
     * @var string
     */
    private $clID;


    const AT_VERIFICATION_RESULT_SUCCESS = 'success';
    const AT_VERIFICATION_RESULT_FAILURE = 'failure';

    const AT_VERFICATION_STATUS_NONE='none';
    const AT_VERFICATION_STATUS_PENDING='pending';
    const AT_VERFICATION_STATUS_SERVERHOLD='serverHold';
    const AT_VERFICATION_STATUS_VERIFIED='verified';
    const AT_VERFICATION_STATUS_FAILED='failed';

    /**
     *
     * @param string $result
     * @param string $verificationDate
     * @param string $method
     * @param string $reference
     * @param string $agent
     * @param ?string $receivedDate
     * @param ?string $clID
     */
    public function  __construct($result = null, 
                                 $verificationDate = null, 
                                 $method = null, 
                                 $reference = null, 
                                 $agent = null, 
                                 $receivedDate = null, 
                                 $clID= null) {
        if ($result) {
            $this->setResult($result);
        }
        if ($verificationDate) {
            $this->setVerificationDate($verificationDate);
        }
        if ($method) {
            $this->setMethod($method);
        }
        if ($reference) {
            $this->setReference($reference);
        }
        if ($agent) {
            $this->setAgent($agent);
        }
        if ($receivedDate) {
            $this->setReceivedDate($receivedDate);
        }
        if ($clID) {
            $this->setClID($clID);
        }
    }

    /* setter */

    public function setResult($result) {
        $this->result = $result;
    }

    public function setVerificationDate($verificationDate) {
        $this->verificationDate = $verificationDate;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function setReference($reference) {
        $this->reference = $reference;
    }

    public function setAgent($agent) {
        $this->agent = $agent;
    }

    public function setReceivedDate($receivedDate) {
        $this->receivedDate = $receivedDate;
    }

    public function setClID($clID) {
        $this->clID = $clID;
    }


    /* getter */

    public function getResult() {
        return $this->result;
    }

    public function getVerificationDate() {
        return $this->verificationDate;
    }

    public function getMethod() {
        return $this->method; 
    }

    public function getReference() {
        return $this->reference;
    }

    public function getAgent() {
        return $this->agent;
    }

    public function getReceivedDate() {
        return $this->receivedDate;
    }

    public function getClID() {
        return $this->clID;
    }


    /**
     * generates the XML 
     * 
     * @param eppRequest $request
     * @param DomElement $ext
     */
    public function exportXML(eppRequest $request, \DomElement $ext) {
        $report = $request->createElement('at-ext-verification:report');

        # mandatory fields
        foreach (['result', 'verificationDate'] as $element) {
        
            $report->appendChild($request->createElement('at-ext-verification:'.$element, $this->$element));
        }

        # optional fields
        foreach (['method', 'reference', 'agent'] as $element) {
            if (!is_null($this->$element)) {
                $report->appendChild($request->createElement('at-ext-verification:'.$element, $this->$element));
            }
        }

        $ext->appendChild($report);

    }

}
